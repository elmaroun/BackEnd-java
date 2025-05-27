<?php
namespace app\Patterns\Command;

use App\Models\Demande;
use App\Models\Client;

use App\Models\TestProfessionnal;
use App\Patterns\Command\ServiceRequestCommand;

class CreateServiceRequestCommand implements ServiceRequestCommand {

    public function __construct(
        private array $data,
        private Client $client,
        private TestProfessionnal $professional
    ) {}

    public function execute() {
        $demande = Demande::create([
            'client_id' => $this->client->id,
            'latitude' => $this->data['latitude'],
            'longitude' => $this->data['longitude'],
            'location' => $this->data['location'],
            'description' => $this->data['description'],
            'date' => now(),
            'statut' => 'en attente',
            'Title' => $this->data['title'],
            'Service' => $this->data['service'],
            'professionnal_id' => $this->professional->id,
        ]);

        // Send notification
        $this->professional->notify(new DemandeCreatedNotification($demande));
    }
}
