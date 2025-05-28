<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Professionnal; 
use App\Models\Transporteur;
use App\Models\TestProfessionnal;
use App\Models\client;
use App\Models\Service;
use App\Models\Demande;
use App\Models\Avis;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Patterns\Builder\ProfessionalBuilder;
use App\Patterns\Builder\Builders\ServiceBuilder;
use App\Patterns\Builder\Builders\TransporteurBuilder;
use App\Patterns\Builder\Builders\ArtisanBuilder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use App\Patterns\Strategy\FilterService;
use App\Patterns\Strategy\SortStrategies\DateSort;
use App\Patterns\Strategy\SortStrategies\DueDateSort;
use App\Patterns\Strategy\SortStrategies\NameSort;
use App\Patterns\Strategy\StatutFiltersStrategies\AcceptedFilter;
use App\Patterns\Strategy\StatutFiltersStrategies\PendingFilter;
use App\Patterns\Strategy\StatutFiltersStrategies\RejectedFilter;
use App\Repositories\RepositoryInterface;
use App\Repositories\EloquentAvisRepository;

use App\Patterns\Command\CreateServiceRequestCommand;
use App\Patterns\Command\RequestHandler;
use App\Patterns\Command\ServiceRequestCommand;




class DemandeController extends Controller
{
   

        
    public function AddDemande(Request $request)
    {
        $credentials = $request->validate([
            'demande_type' => 'required',
            'desired_date' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'demande_domaine' => 'required',

        ]);
        $json = Storage::get('Client_login.json');
        $client = json_decode($json, true);
        $clientId = $client['id'];

        $demande = Demande::create([
            'client_id' => $clientId,
            'date' => $request->desired_date,
            'location' => $request->address,
            'latitude' =>$request->latitude,
            'longitude' =>$request->longitude,
            'statut' => 'En attente',
            'Title' => $request->demande_type,
            'Service' => $request->demande_domaine,


        ]);
    
        return response()->json([
                'success' => true,
                'idDemande' =>$demande->id,
                'message' => 'Successfully added demande',
            ]);
    }
    public function AddDemandeProf(Request $request)
    {
        $credentials = $request->validate([
            'professional_id' => 'required',
            'demande_id' => 'required',
        ]);

        // Check if this professional already has this demande
        $existingDemande = Demande::where([
            'id' => $request->demande_id
        ])->first();

        if (!$existingDemande) {
            return response()->json([
                'success' => false,
                'message' => 'Demande not found',
            ], 404);
        }
        $command = new CreateServiceRequestCommand($existingDemande, $request->professional_id);
        $handler = new RequestHandler();
        $handler->handle($command);

        return response()->json([
            'success' => true,
            'message' => 'done',
        ]);
        


    }

    public function AddDemandeToDB(Request $request)
    {
        $credentials = $request->validate([
            'demande_type' => 'required',
            'desired_date' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'demande_domaine' => 'required',

        ]);
        $json = Storage::get('user_logins.json');
        $user = json_decode($json, true);
        $userId = $user['id'];
        // Get avis with optional eager loading
    
        return response()->json([
                'success' => true,
                'message' => 'Successfully added demande',
            ]);
    }
    public function getNearby($demandeId)
    {
        // Get the demande
        $demande = Demande::findOrFail($demandeId);
        
        // Get professionals within 10km radius (adjust as needed)
        $professionals = TestProfessionnal::select('professionnals.*')
            ->selectRaw('
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance', [
                    $demande->latitude,
                    $demande->longitude,
                    $demande->latitude
                ])
            ->selectRaw("
                CONCAT_WS(', ',
                    TRIM(SUBSTRING_INDEX(location, ',', 1)),
                    TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(location, ',', 2), ',', -1)),
                    TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(location, ',', 3), ',', -1))
                ) AS formatted_address
            ")
            ->where('services',$demande->Service)
            ->withAvg('avis', 'rating')
            ->withCount(['demandes as requests_count' => function($query) {
                $query->where('statut', 'Done');
            }]) // assuming 'note' is the rating column
            ->having('distance', '<', 10)
            ->orderBy('distance')
            ->get();
        $professionals->map(function ($item) {
            $item->img_url = $item->img ? Storage::url($item->img) : null;
            return $item;
        });




        return response()->json([
            'success' => true,
            'professionals' => $professionals,
            'demande' => $demande
        ]);
    }
    public function getDemandeClient()
    {
        $json = Storage::get('Client_login.json');
        $client = json_decode($json, true);
        $clientId = $client['id'];

        $demandeEnCours = Demande::join('clients', 'clients.id', '=', 'demandes.client_id')
            ->where('demandes.client_id', '=', $clientId)
            ->whereIn('statut', ['En attente', 'En cours'])
            ->join('professionnals', 'professionnals.id', '=', 'demandes.professionnal_id')
            ->select('demandes.*','professionnals.nom','professionnals.prenom','professionnals.telephone','professionnals.email','professionnals.services','professionnals.img')
            ->get();
        $demandeEnCours->map(function ($item) {
            $item->img_url = $item->img ? Storage::url($item->img) : null;
            return $item;
        });
        $HistorqueDemande = Demande::join('clients', 'clients.id', '=', 'demandes.client_id')
            ->where('demandes.client_id', '=', $clientId)
            ->whereNotIn('statut', ['en attente', 'en cours'])
            ->join('professionnals', 'professionnals.id', '=', 'demandes.professionnal_id')
            ->select('demandes.*','professionnals.*')
            ->get();

        $HistorqueDemande->map(function ($item) {
            $item->img_url = $item->img ? Storage::url($item->img) : null;
            return $item;
        });
        

        return response()->json([
            'success' => true,
            'demandeEnCours' => $demandeEnCours,
            'HistorqueDemande' => $HistorqueDemande,
        ]);
    }

     public function cancelDemande($id)
    {
    
        // Check if this professional already has this demande
        $existingDemande = Demande::where([
            'id' => $id
        ])->first();

        if (!$existingDemande) {
            return response()->json([
                'success' => false,
                'message' => 'Demande not found',
            ], 404);
        }

        $existingDemande->update([
            'statut' => 'Annulé',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Existing demande updated successfully',
        ]);

    }

   
}
