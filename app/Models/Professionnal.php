<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

abstract class Professionnal extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'ville',
        'location',
        'domaine',
        'services',
        'motdepasse',
        'carte_identite_recto',
        'carte_identite_verso',
        'image_patent',
        'is_patent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'status' => 'boolean', 
    ];

    public function isActive()
    {
        return $this->status === 1;
    }
    public function transporteur()
    {
        return $this->hasOne(Transporteur::class);
    }
    
    public function artisan()
    {
        return $this->hasOne(Transporteur::class);
    }

    public function service()
    {
        return $this->hasOne(Transporteur::class);
    }
}