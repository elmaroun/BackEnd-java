<?php

namespace App\Models;
use App\Interfaces\Professional;
use Illuminate\Database\Eloquent\Model;

class Artisan extends Model
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
