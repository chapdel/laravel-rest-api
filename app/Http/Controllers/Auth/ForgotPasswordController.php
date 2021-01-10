<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetCode;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

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
     * Get the response for a successful password reset code.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetCodeResponse(Request $request, $response)
    {
        return ['status' => trans($response)];
    }

    /**
     * Get the response for a failed password reset code.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetCodeFailedResponse(Request $request, $response)
    {
        if (is_null($request->email)) {
            return response()->json(['email' => trans($response)], 400);
        }
        return response()->json(['phone' => trans($response)], 400);
    }


    /**
     * Send a reset code to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetCode(Request $request)
    {
        $this->validateData($request);

        // We will send the password reset code to this user. Once we have attempted
        // to send the code, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->sendCode(
            $request
        );

        return $response == true
            ? $this->sendResetCodeResponse($request, $response)
            : $this->sendResetCodeFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateData(Request $request)
    {
        $request->validate([
            'email' => [Rule::requiredIf(function () use ($request) {
                return $request->phone == null;
            }), 'exists:users'],
            'phone' => [Rule::requiredIf(function () use ($request) {
                return $request->email == null;
            }), 'phone:CM,AUTO', 'exists:users'],
        ]);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(['email', 'phone']);
    }

    protected function sendCode(Request $request)
    {
        try {
            if (!is_null($request->email)) {
                $user = User::where('email', $request->email)->firstOrFail();
            } else {
                $user = User::where('phone', $request->phone)->firstOrFail();
            }

            $user->notify(new PasswordResetCode());

            return true;
        } catch (Exception $_) {
            return false;
        }
        return true;
    }
}
