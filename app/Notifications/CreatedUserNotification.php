<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class CreatedUserNotification extends Notification
{
    use Queueable;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $signedUrl = URL::temporarySignedRoute(
            name: 'web.confirm-email',
            expiration: now()->addMinutes(5),
            parameters: ['user' => Crypt::encrypt($this->user->id)],
        );

        return (new MailMessage)
                    ->greeting('Olá!')
                    ->line('Bem-vindo ao sistema de gerenciamento de redes sociais')
                    ->action('Confirmar email', $signedUrl)
                    ->line('Obrigado por se registrar!!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
