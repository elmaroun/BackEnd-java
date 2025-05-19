<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;



class client extends Model  implements  Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'ville',
        'adresse',
        'motdepasse',
        'email',
    ];

}