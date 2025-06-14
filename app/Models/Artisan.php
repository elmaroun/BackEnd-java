<?php

namespace App\Models;
use App\Interfaces\Professional;
use Illuminate\Database\Eloquent\Model;

class Artisan extends professionnal
{
    protected $fillable = [
        'services_offerts',
        'specialite',
    ];

    public function professionnal()
    {
        return $this->belongsTo(Professionnal::class);
    }
}
