<?php
namespace App\Patterns\Builder\Builders;

use App\Models\Artisan;
use App\Models\Professionnal;

use App\Patterns\Builder\ProfessionalBuilder;

class ArtisanBuilder implements ProfessionalBuilder {
    private $artisan;

    public function __construct() {
        $this->artisan= new Artisan();
    }

    public function setName($name) {
        $this->artisan->nom = $name;
    }
    public function setPrenom($prenom) {
        $this->artisan->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->artisan->email = $email;
    }

    public function setTelephone($telephone) {
        $this->artisan->telephone = $telephone;
    }

    public function setVille($ville) {
        $this->artisan->ville = $ville;
    }
    public function setLocation($adresse) {
        $this->artisan->location = $adresse;
    }

    public function setDomaine() {
        $this->artisan->domaine = 'travaux'; // Fixed for this type
    }

    public function setServices($services) {
        $this->artisan->services = $services;
    }

    public function setMotDePasse($motdepasse) {
        $this->artisan->motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);
    }

    public function setCarteIdentiteRecto($carte_identite_recto) {
        $this->artisan->carte_identite_recto = $carte_identite_recto;
    }

    public function setCarteIdentiteVerso($carte_identite_verso) {
        $this->artisan->carte_identite_verso = $carte_identite_verso;
    }

    public function setIsPatent($is_patent) {
        $this->artisan->is_patent = $is_patent;
    }

    public function setImagePatent($image_patent) {
        $this->artisan->image_patent = $image_patent;
    }

    public function setServiceOfferts($services_offerts) {
        $this->artisan->services_offerts = $services_offerts;
    }
    public function setSpecialite($specialite) {
        $this->artisan->specialite = $specialite;
    }

    public function getProfessional(): Professionnal {
        return $this->artisan;
    }
}