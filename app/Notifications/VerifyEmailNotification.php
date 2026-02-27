<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $verificationToken;

    /**
     * Create a new notification instance.
     */
    public function __construct($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = url('/api/verify-email?token=' . $this->verificationToken);

        return (new MailMessage)
            ->subject('Xác thực email của bạn')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Cảm ơn bạn đã đăng ký tài khoản.')
            ->line('Vui lòng click vào nút bên dưới để xác thực email của bạn:')
            ->action('Xác thực Email', $verificationUrl)
            ->line('Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.')
            ->salutation('Trân trọng, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'verification_token' => $this->verificationToken
        ];
    }
}