<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Recuperación de Contraseña — GraCitas Coracora')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line('Recibiste este correo electrónico porque solicitaste restablecer la contraseña de tu cuenta en la plataforma deportiva GraCitas.')
            ->action('Restablecer mi Contraseña', $url)
            ->line('Este enlace de recuperación de contraseña expirará en 60 minutos.')
            ->line('Si tú no solicitaste este cambio, puedes ignorar este mensaje; tu contraseña actual continuará siendo segura.')
            ->salutation('Atentamente,'.PHP_EOL.'El equipo de GraCitas Coracora');
    }
}
