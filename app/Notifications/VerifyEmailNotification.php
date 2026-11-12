<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu correo electrónico — GraCitas Coracora')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line('¡Gracias por unirte a GraCitas, la plataforma de reservas de canchas de gras sintético en Coracora!')
            ->line('Por favor, haz clic en el siguiente botón para verificar tu dirección de correo electrónico y activar todas las funciones de tu cuenta:')
            ->action('Verificar Dirección de Correo', $verificationUrl)
            ->line('Este enlace de verificación es seguro y expirará en '.Config::get('auth.verification.expire', 60).' minutos.')
            ->line('Si no creaste una cuenta en GraCitas, no es necesario realizar ninguna acción.')
            ->salutation('Atentamente,'.PHP_EOL.'El equipo de GraCitas Coracora');
    }

    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
