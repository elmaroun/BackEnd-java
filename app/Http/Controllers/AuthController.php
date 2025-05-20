<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Professionnal; 
use App\Models\Transporteur;
use App\Models\TestProfessionnal;
use App\Models\client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Patterns\Builder\ProfessionalBuilder;
use App\Patterns\Builder\Builders\ServiceBuilder;
use App\Patterns\Builder\Builders\TransporteurBuilder;
use App\Patterns\Builder\Builders\ArtisanBuilder;

use Illuminate\Support\Facades\File;






class AuthController extends Controller
{


    
    public function loginProfessional(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $professional = TestProfessionnal::where('email', $credentials['email'])->first();

        if ($professional && Hash::check($credentials['password'], $professional->motdepasse)) {
            Auth::login($professional);
            return response()->json([
                'success' => true,
                'message' => 'Successfully authenticated',
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ]);
    }
    public function loginClient(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = client::where('email', $credentials['email'])->first();

        if ($client && password_verify($credentials['password'], $client->motdepasse)) {
            Auth::login($client);
            return response()->json([
                'success' => true,
                'message' => 'Successfully authenticated',
                'user' => [
                    'id' => Auth::id(),
                    'nom' => Auth::user()->nom,
                    'email' => Auth::user()->email,
                ]
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'password incorrect#',
                
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ]);
    }

    public function RegisterTransporteur(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'nom' => 'string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:professionnals,email', 
            'phone' => 'required|string|max:20', 
            'ville' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'domaine' => 'required|string|max:255',
            'motdepasse' => 'required|string|min:8',            
          
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        if ($request->input('domaine') == 'transports') {
    
            $builder = new TransporteurBuilder();
            $builder->setName($request->input('nom'));
            $builder->setPrenom($request->input('prenom'));
            $builder->setEmail($request->input('email'));
            $builder->setTelephone($request->input('phone'));
            $builder->setVille($request->input('ville'));

            $builder->setDomaine(); 
            $builder->setMotDePasse($request->input('motdepasse'));

            $transporteur= $builder->getProfessional();
            file_put_contents('builder_state.txt', serialize($builder));
            session(['builder' => $builder]);
            return response()->json([
                'success' => true,
                'message' => 'Transporteur first step registered successfully!',
                'data' => $transporteur,
                
            ], 201);
        }elseif($request->input('domaine') == 'travaux'){
            $builder = new ArtisanBuilder();
            $builder->setName($request->input('nom'));
            $builder->setPrenom($request->input('prenom'));
            $builder->setEmail($request->input('email'));
            $builder->setTelephone($request->input('phone'));
            $builder->setVille($request->input('ville'));

            $builder->setDomaine(); 
            $builder->setMotDePasse($request->input('motdepasse'));

            $artisan= $builder->getProfessional();
            file_put_contents('builder_state.txt', serialize($builder));
            session(['builder' => $builder]);
            return response()->json([
                'success' => true,
                'message' => 'Artisan first step registered successfully!',
                'data' => $artisan,
                
            ], 201);
        }elseif($request->input('domaine') == 'services'){
            $builder = new ServiceBuilder();
            $builder->setName($request->input('nom'));
            $builder->setPrenom($request->input('prenom'));
            $builder->setEmail($request->input('email'));
            $builder->setTelephone($request->input('phone'));
            $builder->setVille($request->input('ville'));

            $builder->setDomaine(); 
            $builder->setMotDePasse($request->input('motdepasse'));

            $service= $builder->getProfessional();
            file_put_contents('builder_state.txt', serialize($builder));
            session(['builder' => $builder]);
            return response()->json([
                'success' => true,
                'message' => 'service first step registered successfully!',
                'data' => $service,
                
            ], 201);
        }
    }

   public function RegisterTransporteur2(Request $request)
    {
        $serialized = file_get_contents('builder_state.txt');
        $builder = unserialize($serialized);
        $professional1 = $builder->getProfessional();

        if (!$builder) {
             return response()->json([
            'success' => false,
            'message' => 'not working',
            'data' => $professional1,

            ], 201);
        }
        $validator = Validator::make($request->all(), [
            'carte_identite_recto' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'carte_identite_verso' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'is_patent' => 'required|boolean',
            'img_patent' => 'required_if:is_patent,1|file|mimes:jpg,jpeg,png,pdf',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $domaine = $professional1->domaine;
        if ($domaine == "transports" ) {
            $builder->setIsPatent($request->input('is_patent'));
            if ($request->hasFile('carte_identite_recto')) {
                $path = $request->file('carte_identite_recto')->store('carte_identite_recto');
                $builder->setCarteIdentiteRecto($path);
            }
            if ($request->hasFile('img_patent')) {
                $path = $request->file('img_patent')->store('img_patent');
                $builder->setImagePatent($path);
            }
            
            if ($request->hasFile('carte_identite_verso')) {
                $path = $request->file('carte_identite_verso')->store('carte_identite_verso');
                $builder->setCarteIdentiteVerso($path);
            }

            file_put_contents('builder_state.txt', serialize($builder));
            $professional1 = $builder->getProfessional();
            return response()->json([
                'success' => true,
                'message' => 'Transporteur registered2 successfully!',
                'results' => 'transports',
                'data' => $professional1
            ], 201);

        }elseif($domaine == 'travaux'){

            $builder->setIsPatent($request->input('is_patent'));
            if ($request->hasFile('carte_identite_recto')) {
                $path = $request->file('carte_identite_recto')->store('carte_identite_recto');
                $builder->setCarteIdentiteRecto($path);
            }
            if ($request->hasFile('img_patent')) {
                $path = $request->file('img_patent')->store('img_patent');
                $builder->setImagePatent($path);
            }
            
            if ($request->hasFile('carte_identite_verso')) {
                $path = $request->file('carte_identite_verso')->store('carte_identite_verso');
                $builder->setCarteIdentiteVerso($path);
            }

            file_put_contents('builder_state.txt', serialize($builder));
            $professional = $builder->getProfessional();
            return response()->json([
                'success' => true,
                'message' => 'Transporteur registered2 successfully!',
                'results' => 'travaux',
                'data' => $professional
            ], 201);

        }elseif($professional1->domaine == 'services'){
            $builder->setIsPatent($request->input('is_patent'));
            if ($request->hasFile('carte_identite_recto')) {
                $path = $request->file('carte_identite_recto')->store('carte_identite_recto');
                $builder->setCarteIdentiteRecto($path);
            }
            if ($request->hasFile('img_patent')) {
                $path = $request->file('img_patent')->store('img_patent');
                $builder->setImagePatent($path);
            }
            if ($request->hasFile('carte_identite_verso')) {
                $path = $request->file('carte_identite_verso')->store('carte_identite_verso');
                $builder->setCarteIdentiteVerso($path);
            }

            file_put_contents('builder_state.txt', serialize($builder));
            $professional = $builder->getProfessional();
            return response()->json([
                'success' => true,
                'message' => 'Transporteur registered2 successfully!',
                'results' => 'services',
                'data' => $professional
            ], 201);

        }


    }


    public function RegisterTransporteur3(Request $request)
    {
        $serialized = file_get_contents('builder_state.txt');
        $builder = unserialize($serialized);

        if (!$builder) {
             return response()->json([
            'success' => false,
            'message' => 'not working',
            'data' => $builder,

            ], 201);
        }

        $validator = Validator::make($request->all(), [
            'image_vehicule' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'charge_max' => 'required|string',
            'type_vehicule' => 'required|string',
            'acceptConditions' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $builder->setChargeMax($request->input('charge_max'));
        $builder->setTypeVehicule($request->input('type_vehicule'));

        if ($request->hasFile('image_vehicule')) {
            $path = $request->file('image_vehicule')->store('image_vehicule');
            $builder->setImageVehicule($path);
        
        }
        file_put_contents('builder_state.txt', serialize($builder));
        $transporteur = $builder->getProfessional();
        $professional = TestProfessionnal::create([
            'nom' => $transporteur->nom,
            'prenom' => $transporteur->prenom,
            'telephone' => $transporteur->telephone,
            'email' => $transporteur->email,
            'ville' => $transporteur->ville,
            'location' => $transporteur->location,
            'domaine' => $transporteur->domaine,
            'services' => $transporteur->services,
            'motdepasse' => $transporteur->motdepasse,
            'carte_identite_recto' => $transporteur->carte_identite_recto,
            'carte_identite_verso' => $transporteur->carte_identite_verso,
            'image_patent' => $transporteur->image_patent,
            'is_patent' => $transporteur->is_patent,
        ]);
        $Transporteur = Transporteur::create([
            'professionnal_id' => $professional->id,
            'image_vehicule' => $transporteur->image_vehicule,
            'charge_max' => $transporteur->prenom,
            'type_vehicule' => $transporteur->telephone,
           
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Transporteur registered2 successfully!',
            'data' => $professional
        ], 201);
    }

    public function registerClient(Request $request)
    {


        $professional = client::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'motdepasse' => Hash::make($request->motdepasse)
        ]);
        
       

        return response()->json([
            'success' => true,
            'message' => 'Client registered successfully!',
            'data' => $professional], 201);
    }
    public function uploadImageVehicule($image)
    {
        if ($image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/ImageVehicule'), $imageName);
            return 'uploads/ImageVehicule/' . $imageName;
        }
        return null;
    }
    public function uploadImagePatente($image)
    {
        if ($image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/ImagePatente'), $imageName);
            return 'uploads/ImagePatent/' . $imageName;
        }
        return null;
    }

    public function uploadImageCIN($image)
    {
        if ($image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/ImageCIN'), $imageName);
            return 'uploads/ImageCIN/' . $imageName;
        }
        return null;
    }


    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully authenticated',
                'user' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials are incorrect.',
        ], 401);
    }
    
}
