<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\MustVerifyAccount;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Rules\UnBanned;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [Rule::requiredIf(!isset($data['phone'])), 'email', 'max:255', 'unique:users', new UnBanned('users')],
            'phone' => [Rule::requiredIf(!isset($data['email'])), 'phone:CM,AUTO', 'unique:users', new UnBanned('users')],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => isset($data['email']) ? $data['email'] : null,
            'phone' =>  isset($data['phone']) ? $data['phone'] : null,
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return abort('authenticated');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function registered(Request $request, User $user)
    {

        if ($user instanceof MustVerifyAccount) {
            $user->sendVerificationNotification();
            $user = auth()->user();
            return response()->json([
                'token' => (string) $user->createToken('api')->plainTextToken,
                'user' => $user,
                'verified' => false,
                'token_type' => 'Bearer',
            ]);
        }

        $user = auth()->user();
        return response()->json([
            'token' => (string) $user->createToken('api')->plainTextToken,
            'user' => $user,
            'token_type' => 'Bearer',
        ]);
    }
}
