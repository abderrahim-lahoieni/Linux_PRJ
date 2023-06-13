<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class validation extends Notification
{
    use Queueable;

    protected $intituleIntervention;

    public function __construct($intituleIntervention)
    {
        $this->intituleIntervention = $intituleIntervention;
    }

    public function via($notifiable)
    {
        return ['mail']; // Choisissez les canaux par lesquels vous souhaitez envoyer la notification (mail, SMS, notifications push, etc.)
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Intervention validée')
            ->line('L\'intervention "'.$this->intituleIntervention.'" a été validée par le président.')
            ->line('Merci pour votre participation.')
            ->salutation('Cordialement');
        }
}