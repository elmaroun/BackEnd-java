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
            'service' => 'required|string|max:255',
            'motdepasse' => 'required|string|min:8', 
            'type_vehicule' => 'required|string|max:255',
            'chargeMax' => 'required|numeric', 
            'vehicleImage' => 'nullable|image|mimes:jpg,jpeg,png,heic', 
            'carteRecto' => 'nullable|image|mimes:jpg,jpeg,png,heic',
            'carteVerso' => 'nullable|image|mimes:jpg,jpeg,png,heic',
            'patenteFile' => 'nullable|mimes:jpg,jpeg,png,pdf,heic' ,           
          
        ]);
        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $professional = TestProfessionnal::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->phone,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'domaine' => $request->domaine,
            'service' => $request->service,
            'email' => $request->email,
            'motdepasse' => Hash::make($request->motdepasse),
            'carte_identite_recto' => $this-> uploadImageCIN($request->carteRecto),
            'carte_identite_verso' => $this-> uploadImageCIN($request->carteVerso),
            'image_patent' => $this->uploadImagePatente($request->patenteFile),
            'is_patent'
            
        ]);
        
        $transporteur = Transporteur::create([
            'professionnal_id' => $professional->id, 
            'charge_max' => $request->chargeMax,
            'type_vehicule' => $request->type_vehicule,
            'image_vehicule' => $this->uploadImageVehicule($request->vehicleImage),
        ]);

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Professional registered successfully!',
            'data' => $professional], 201);
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
