<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApiResetPassword extends Notification
{
    use Queueable;
    protected $reset_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->reset_code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Código de Verificación')
                    ->line('Se ha registrado una solicitud de cambio de contraseña para esta cuenta.')
                    ->line('Por favor ingrese este codigo en el sistema para continuar con la solicitud:')
                    ->line($this->reset_code)
                    ->line('Si no ha solicitado el cambio de contraseña por favor ignore esta notificacion.')
                    ->subject('Cambio de Contraseña - SISCORUDO');
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
}
