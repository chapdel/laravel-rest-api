<?php

namespace App\Interfaces;

interface MustVerifyAccount
{
    /**
     * Determine if the user has verified their account.
     *
     * @return bool
     */
    public function hasVerifiedAccount();

    /**
     * Mark the given user's account as verified.
     *
     * @return bool
     */
    public function markAccountAsVerified();

    /**
     * Send the account verification notification.
     *
     * @return void
     */
    public function sendVerificationNotification();

    /**
     * Get the account that should be used for verification.
     *
     * @return string
     */
    public function getAccountForVerification();
}
