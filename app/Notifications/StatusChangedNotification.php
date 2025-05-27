<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification; 
use App\Models\Demande; // THIS WAS MISSING


class StatusChangedNotification extends Notification
{
    public function __construct(
        public Demande $demande,
        public string $newStatus,
        public string $recipientType,

    ) {}
    public function via($notifiable)
    {
        return ['mail'];
    }

public function toMail($notifiable): MailMessage
{
    $message = new MailMessage();

    if ($notifiable instanceof \App\Models\Client) {
        $message->subject("Mise à jour de votre demande")
                ->greeting("Bonjour {$notifiable->nom} {$notifiable->prenom},")
                ->line("Le statut de votre demande a été mis à jour à : {$this->newStatus}.")
                ->action('Voir votre demande', "http://localhost:3000/home-client")
                ->line("Merci de faire confiance à notre plateforme.");
    }

    // Si le destinataire est un professionnel
    elseif ($notifiable instanceof \App\Models\TestProfessionnal) {
        $message->subject("Nouvelle demande à traiter")
                ->greeting("Bonjour {$notifiable->nom} {$notifiable->prenom},")
                ->line("Une demande dont vous êtes responsable est passée à l'état : {$this->newStatus}.")
                ->action('Voir la demande', "http://localhost:3000/admin/table")
                ->line("Merci pour votre réactivité.");
    }

    return $message;
}
}