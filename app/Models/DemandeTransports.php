<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeTransports extends Model
{
    protected $fillable = [
        'demande_id',
        'point_arrivee',
        'type_marchandise',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
