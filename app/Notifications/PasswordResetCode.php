<?php

namespace App\Notifications;

use App\Models\UserVerification as ModelsUserVerification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class PasswordResetCode extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->email)
            return ['mail'];
        else
            return ['nexmo'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $tmp =  random_int(100000, 999999);
        $code = ModelsUserVerification::create(['user_id' => $notifiable->id, 'code' => $tmp, 'expire_at' => Carbon::now()->addDay(), 'type' => 'password']);

        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line(__('Your verification code is') . ' ' . $code->code)
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }


    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $verification = $notifiable->verifications()->create([
            'code' => random_int(100000, 999999),
            'expire_in' => now()->addHours(5)
        ]);
        return (new NexmoMessage)
            ->content(__("Your password verification code is " . $verification->code));
    }
}
