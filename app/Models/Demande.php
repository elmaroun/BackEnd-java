<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'client_id',
        'latitude',
        'longtitude',
        'professional_id',
        'location',
        'description',
        'date',
        'statut',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function professional()
    {
        return $this->morphTo();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
