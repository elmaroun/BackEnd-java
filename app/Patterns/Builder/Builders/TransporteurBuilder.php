<?php
namespace App\Patterns\Builder\Builders;

use App\Models\Transporteur;
use App\Models\Professionnal;

use App\Patterns\Builder\ProfessionalBuilder;

class TransporteurBuilder implements ProfessionalBuilder {
    private $transporteur;

    public function __construct() {
        $this->transporteur = new Transporteur();
    }

    public function setName($name) {
        $this->transporteur->nom = $name;
    }
    public function setPrenom($prenom) {
        $this->transporteur->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->transporteur->email = $email;
    }

    public function setTelephone($telephone) {
        $this->transporteur->telephone = $telephone;
    }

    public function setVille($ville) {
        $this->transporteur->ville = $ville;
    }
    public function setLocation($adresse) {
        $this->transporteur->location = $adresse;
    }

    public function setDomaine() {
        $this->transporteur->domaine = 'transports'; // Fixed for this type
    }

    public function setServices($services) {
        $this->transporteur->services = $services;
    }

    public function setMotDePasse($motdepasse) {
        $this->transporteur->motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);
    }

    public function setCarteIdentiteRecto($carte_identite_recto) {
        $this->transporteur->carte_identite_recto = $carte_identite_recto;
    }

    public function setCarteIdentiteVerso($carte_identite_verso) {
        $this->transporteur->carte_identite_verso = $carte_identite_verso;
    }

    public function setIsPatent($is_patent) {
        $this->transporteur->is_patent = $is_patent;
    }

    public function setImagePatent($image_patent) {
        $this->transporteur->image_patent = $image_patent;
    }

    public function setImageVehicule($imagevehicule) {
        $this->transporteur->image_vehicule = $imagevehicule;
    }

    public function setChargeMax($ChargeMax) {
        $this->transporteur->charge_max = $ChargeMax;
    } 

    public function setTypeVehicule($type_vehicule) {
        $this->transporteur->type_vehicule = $type_vehicule;
    }

    public function getProfessional(): Professionnal {
        return $this->transporteur;
    }
}