<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Professionnal; // Correction ici

class Transporteur extends professionnal
{
    protected $fillable = [
        'professionnal_id',
        'image_vehicule',
        'charge_max',
        'type_vehicule',
    ];
    public function professionnal()
    {
        return $this->belongsTo(Professionnal::class);
    }
}
