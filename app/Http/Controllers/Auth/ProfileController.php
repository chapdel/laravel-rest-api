<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\UnBanned;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            "name" => ['required', 'string', 'min:3'],
            'email' => [Rule::requiredIf($request->phone == null), 'email', 'unique:users,email,' . $user->id, new UnBanned('users')],
            'phone' => [Rule::requiredIf($request->email == null), 'phone:AUTO,CM', 'unique:users,phone,' . $user->id, new UnBanned('users')],
        ]);


        if ($request->email) {
            $user->email = $request->email;
        }

        if ($request->phone) {
            $user->phone = $request->phone;
        }

        $user->name = $request->name;

        $user->save();

        return response()->json($user->refresh());
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|password:sanctum',
            'password' => 'required|confirmed|min:8',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json($request->user()->update([
            'password' => bcrypt($request->password)
        ]));
    }
}
