<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class VerifyAccountNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verification = $notifiable->verifications()->create([
            'code' => random_int(100000, 999999),
            'expire_in' => now()->addHours(5)
        ]);

        return (new MailMessage)
            ->subject(__('Verify your ' . config('app.name') . ' account'))
            ->greeting(__("Hello " . $notifiable->name) . '!')
            ->line(__("Your verification code is " . $verification->code))
            ->line(__('This code is valid for 5 Hours!'));
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
            ->content(__("Your verification code is " . $verification->code));
    }
}
