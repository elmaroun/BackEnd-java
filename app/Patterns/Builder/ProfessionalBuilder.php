<?php
namespace App\Patterns\Builder;

use App\Models\Professionnal;


interface ProfessionalBuilder {
    public function setName($name);
    public function setEmail($email);
    public function setTelephone($telephone);
    public function setVille($ville);
    public function setDomaine();
    public function setServices($services);
    public function setMotDePasse($motdepasse);
    public function setCarteIdentiteRecto($carte_identite_recto);
    public function setCarteIdentiteVerso($carte_identite_verso);
    public function setIsPatent($is_patent);
    public function setImagePatent($image_patent);
    public function setImageProf($image_patent);

    public function getProfessional(): Professionnal;
}