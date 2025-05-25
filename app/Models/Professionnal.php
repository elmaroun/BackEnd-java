<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Travaux;


abstract class Professionnal extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'img',
        'nom',
        'prenom',
        'latitude',
        'longitude',
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