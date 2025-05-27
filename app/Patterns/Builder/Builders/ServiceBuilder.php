<?php
namespace App\Patterns\Builder\Builders;

use App\Models\Service;
use App\Models\Professionnal;

use App\Patterns\Builder\ProfessionalBuilder;

class ServiceBuilder implements ProfessionalBuilder {
    private $service;

    public function __construct() {
        $this->service= new Service();
    }

    public function setName($name) {
        $this->service->nom = $name;
    }
    public function setPrenom($prenom) {
        $this->service->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->service->email = $email;
    }

    public function setLatitude($latitude) {
        $this->service->latitude = $latitude;
    } 
    public function setLongitude($longitude) {
        $this->service->longitude = $longitude;
    }
    public function setImageProf($imageprofile) {
        $this->service->img = $imageprofile;
    }
    public function setTelephone($telephone) {
        $this->service->telephone = $telephone;
    }

    public function setVille($ville) {
        $this->service->ville = $ville;
    }
    public function setLocation($adresse) {
        $this->service->location = $adresse;
    }

    public function setDomaine() {
        $this->service->domaine = 'services'; // Fixed for this type
    }

    public function setServices($services) {
        $this->service->services = $services;
    }

    public function setMotDePasse($motdepasse) {
        $this->service->motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);
    }

    public function setCarteIdentiteRecto($carte_identite_recto) {
        $this->service->carte_identite_recto = $carte_identite_recto;
    }

    public function setCarteIdentiteVerso($carte_identite_verso) {
        $this->service->carte_identite_verso = $carte_identite_verso;
    }

    public function setIsPatent($is_patent) {
        $this->service->is_patent = $is_patent;
    }

    public function setImagePatent($image_patent) {
        $this->service->image_patent = $image_patent;
    }

    public function setServiceOfferts($services_offerts) {
        $this->service->services_offerts = $services_offerts;
    }
    public function setSpecialite($specialite) {
        $this->service->specialite = $specialite;
    }

    public function getProfessional(): Professionnal {
        return $this->service;
    }
}