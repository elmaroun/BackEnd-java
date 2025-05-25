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
use App\Repositories\AvisRepositoryInterface;
use App\Repositories\EloquentAvisRepository;



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
        $json = Storage::get('user_logins.json');
        $user = json_decode($json, true);
        $userId = $user['id'];

        $demande = Demande::create([
            'client_id' => 2,
            'date' => $request->desired_date,
            'location' => $request->address,
            'statut' => 'En cours',

        ]);
    
        return response()->json([
                'success' => true,
                'idDemande' =>$demande->id,
                'message' => 'Successfully added demande',
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
            ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [
                $demande->latitude,
                $demande->longitude,
                $demande->latitude
            ])
            ->having('distance', '<', 10) 
            ->orderBy('distance')
            ->get();

        return response()->json([
            'success' => true,
            'professionals' => $professionals,
            'demande' => $demande
        ]);
    }







    

    
}
