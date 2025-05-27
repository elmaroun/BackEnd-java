<?php

namespace App\Observers;

use App\Models\Demande;
use App\Notifications\StatusChangedNotification;

class EmailObserver implements DemandeObserver
{
    protected $demande;

    public function __construct(Demande $demande)
    {
        $this->demande = $demande->load('client', 'professional');
    }

    public function notifyStatusChange(string $newStatus)
    {
        // Notify client
        $this->demande->client->notify(
            new StatusChangedNotification($this->demande, $newStatus, 'client')
        );

        // Notify professional
        $this->demande->professional->notify(
            new StatusChangedNotification($this->demande, $newStatus, 'professional')
        );
    }

    public function notifyDoneService(string $newStatus)
    {
        // Notify client
        $this->demande->client->notify(
            new StatusChangedNotification($this->demande, $newStatus, 'done')
        );


       
    }
}