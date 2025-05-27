<?php
namespace App\Patterns\Command;

use App\Models\Demande;
use App\Models\Client;

use App\Models\TestProfessionnal;
use App\Patterns\Command\ServiceRequestCommand;

class CreateServiceRequestCommand implements ServiceRequestCommand {

    public function __construct(
        private Demande $demande,
        private int $professional_id
    ) {}

    public function execute() {

        if ($this->demande->professionnal_id ==null) {
            $this->demande->update([
                'professionnal_id' => $this->professional_id,
            ]);

            return response()->json([
                'success' => true,
                'action' => 'updated',
                'idDemande' => $this->demande->id,
                'message' => 'Existing demande updated successfully',
            ]);
        }

        $newDemandeData = $this->demande->toArray();
        $newDemandeData['professionnal_id'] = $this->professional_id;

        // Optionally unset fields you don't want to copy (like id, timestamps)
        unset($newDemandeData['id'], $newDemandeData['created_at'], $newDemandeData['updated_at']);

        $demande = Demande::create($newDemandeData);

        return response()->json([
            'success' => true,
            'action' => 'created',
            'idDemande' => $demande->id,
            'message' => 'New demande created successfully',
        ]);

    }
}
