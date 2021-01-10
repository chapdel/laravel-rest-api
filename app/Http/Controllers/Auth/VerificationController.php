<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Account Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|min:6']);

        $user = auth()->user();

        $valid = UserVerification::whereCode($request->code)->whereUserId($user->id)->first();


        if ($user->hasVerifiedAccount()) {
            return response()->json([
                'status' => trans('verification.already_verified'),
            ], 400);
        }

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => [trans('verification.invalid')],
            ]);
        }

        if ($valid->expire_in < now()) {
            throw ValidationException::withMessages([
                'code' => [trans('verification.user')],
            ]);
        }

        $user->markAccountAsVerified();

        event(new Verified($user));

        return response()->json([
            'status' => trans('verification.verified'),
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        if (is_null($user)) {
            if (is_null($user->email) && !is_null($user->phone)) {
                throw ValidationException::withMessages([
                    'email' => [trans('verification.user')],
                ]);
            } else if (!is_null($user->email) && is_null($user->phone)) {
                throw ValidationException::withMessages([
                    'phone' => [trans('verification.user')],
                ]);
            }
        }

        if ($user->hasVerifiedAccount()) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.already_verified')],
            ]);
        }

        $code = $user->codeExpired();

        if ($code == 'empty' || $code == 'expired') {
            $user->sendVerificationNotification();
        } else {
            $user->sendVerificationNotification($code);
        }

        return response()->json(trans('verification.sent'));
    }
}
