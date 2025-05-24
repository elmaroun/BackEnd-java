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



class AvisProfController extends Controller
{
    protected $avisRepository;
    public function __construct(AvisRepositoryInterface $avisRepository)
    {
        $this->avisRepository = $avisRepository;
    }

        
    public function GetAvisProfessionnal()
    {
            $json = Storage::get('user_logins.json');

            $user = json_decode($json, true);
            $userId = $user['id'];
            // Get avis with optional eager loading
            $avis = $this->avisRepository->getByProfessional($userId);
            

            return response()->json([
                'success' => true,
                'data' => $avis
            ]);
    }




    

    
}
