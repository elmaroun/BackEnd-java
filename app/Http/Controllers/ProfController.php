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



class ProfController extends Controller
{
    protected $avisRepository;

        public function Dashboard()
        {
            $json = Storage::get('user_logins.json');

            $user = json_decode($json, true);
            $userId = $user['id'];
            $Demandes_recues = Demande::with('client')
                ->where('professionnal_id', $userId)
                ->where('statut','En Attente')
                ->limit(3)
                ->get();

            $Demandes_count = Demande::where('professionnal_id', $userId)->count();
            $Demandes_accepte_count = Demande::where('professionnal_id', $userId)
                ->where('statut','acceptée')
                ->count();
            $today = Carbon::today();

            $Demandes_future_count = Demande::where('professionnal_id', $userId)
                ->where('statut', 'acceptée')
                ->where('date', '>', $today)
                ->count();


        return response()->json([
            'user' =>$user,
            'Demandes_recues' => $Demandes_recues,
            'Demandes_count' => $Demandes_count,
            'Demandes_accepte_count' => $Demandes_accepte_count,
            'Demandes_future_count' => $Demandes_future_count

        ]);
        }

        public function AvisRecus(Request $request)
        {
            $json = Storage::get('user_logins.json');

            $user = json_decode($json, true);
            $userId = $user['id'];
             $query = Demande::with('client')
                ->where('professionnal_id', $userId);

            if ($request->has('name') && !empty($request->name)) {
                $query->whereHas('client', function($q) use ($request) {
                    $q->where('nom', 'like', '%'.$request->name.'%')
                    ->orWhere('prenom', 'like', '%'.$request->name.'%');
                });
            }
            $filterService = new FilterService();


            if ($request->has('status') && !empty($request->status)) {
                switch ($request->status) {
                    case 'acceptée':
                        $filterService->setStrategy(new AcceptedFilter());
                        break;
                    case 'En Attente':
                        $filterService->setStrategy(new PendingFilter());
                        break;
                    case 'refusée':
                        $filterService->setStrategy(new RejectedFilter());
                        break;
                }
                $query = $filterService->apply($query);
            }

            $sortField = $request->get('sort', 'created_at');
            
            $sortStrategy = match($request->get('sort', 'created_at')) {
                'nom' => new NameSort(),
                'date' => new DueDateSort(),
                default => new DateSort()
            };

            $filterService->setStrategy($sortStrategy);
            $query = $filterService->apply($query);

            $demandes = $query->get();

            return response()->json([
                'Demandes_recues' => $demandes
            ]);

        }


        public function show($id)
        {
            $demande = Demande::findOrFail($id);

            // Calculate rating stats
           
            return response()->json([
                'demande' => $demande,
                'client' => $demande->client,
            ]);
        }

        public function GetProfile()
        {

            $json = Storage::get('user_logins.json');

            $user = json_decode($json, true);
            $userId = $user['id'];
            $user = TestProfessionnal::findOrFail($userId);
            $travaux = DB::table('professionnals')
                ->join('travaux', 'professionnals.id', '=', 'travaux.professionnal_id')
                ->where('professionnals.id', $userId)
                ->select( 'travaux.*')
                ->get();
            return response()->json([
                'travaux' => $travaux,
                'user'=>$user,
            ]);
        }

        public function accept($id)
        {
            $demande = Demande::findOrFail($id);
            $demande->update(['statut' => 'acceptée']);
            
            return response()->json(['message' => 'Demande acceptée']);
        }

        public function refuse($id)
        {
            $demande = Demande::findOrFail($id);
            $demande->update(['status' => 'Refusée']);
            
            return response()->json(['message' => 'Demande refusée']);
        }

        public function UpdateProfile(Request $request)
        {
            try {
                $json = Storage::get('user_logins.json');
                $user = json_decode($json, true);
                $userId = $user['id'];
                $professional = TestProfessionnal::findOrFail($userId);

                $validated = $request->validate([
                    'nom' => 'required',
                    'prenom' => 'required',
                    'email' => 'required|email',
                    'telephone' => 'nullable',
                    'location' => 'nullable',
                    'ville' => 'nullable',
                ]);

                $professional->update($validated);

                $user['nom'] = $validated['nom'];
                $user['prenom'] = $validated['prenom'];
                $user['email'] = $validated['email'];
                $user['telephone'] = $validated['telephone'] ?? $user['telephone'] ?? null;
                $user['location'] = $validated['location'] ?? $user['location'] ?? null;
                $user['ville'] = $validated['ville'] ?? $user['ville'] ?? null;

                Storage::put('user_logins.json', json_encode($user, JSON_PRETTY_PRINT));

                return response()->json(['message' => 'Profil mis à jour avec succès', 'user' => $user]);

            } catch (ValidationException $e) {
                // Return validation errors as JSON with 422 status
                return response()->json(['errors' => $e->errors()], 422);
            } catch (\Exception $e) {
                // Return any other errors as JSON with 500 status
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }






    

    
}
