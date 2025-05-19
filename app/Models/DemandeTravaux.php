<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeTravaux extends Model
{
    protected $fillable = [
        'demande_id',
        // Add more specific fields for travaux if needed
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
