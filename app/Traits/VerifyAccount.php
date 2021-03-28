<?php

namespace App\Traits;

use App\Notifications\VerifyAccountNotification;
use Carbon\Carbon;

/**
 * Account verification trait
 */
trait VerifyAccount
{
    /**
     * Determine if the user has verified their account.
     *
     * @return bool
     */
    public function hasVerifiedAccount()
    {
        if (!is_null($this->email)) {
            return !is_null($this->email_verified_at);
        } else {
            return !is_null($this->phone_verified_at);
        }
    }

    /**
     * Mark the given user's account as verified.
     *
     * @return bool
     */
    public function markAccountAsVerified()
    {
        if (!is_null($this->email)) {
            return $this->forceFill([
                'email_verified_at' => $this->freshTimestamp(),
            ])->save();
        } else {
            return $this->forceFill([
                'phone_verified_at' => $this->freshTimestamp(),
            ])->save();
        }
    }

    /**
     * Send the account verification notification.
     *
     * @return void
     */
    public function sendVerificationNotification()
    {
        $this->notify(new VerifyAccountNotification);
    }

    /**
     * Get the account that should be used for verification.
     *
     * @return string
     */
    public function getAccountForVerification()
    {
        return !is_null($this->email) ? $this->email : $this->phone;
    }

    public function codeExpired()
    {

        $code = $this->verifications()->orderBy('id', 'desc')->first();


        if (is_null($code)) {
            return 'empty';
        } else {
            if ($code->expire_on >= Carbon::now()) {
                return $code->code;
            } else {
                return 'expired';
            }
        }
    }

    public function verifications()
    {
        return $this->morphMany(Verification::class, 'verifiable');
    }
}
