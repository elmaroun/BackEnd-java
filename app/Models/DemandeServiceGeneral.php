<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeServiceGeneral extends Model
{
    protected $fillable = [
        'demande_id',
        'frequence',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
