<?php
namespace app\Patterns\Command;

use App\Models\Demande;
use App\Models\Client;
use App\Patterns\Command\ServiceRequestCommand;

class RequestHandler  {

    public function handle(ServiceRequestCommand $command) {
        $command->execute();
    }
}
