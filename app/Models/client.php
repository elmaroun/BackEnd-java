<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Notifications\Notifiable;



class client extends Model  implements  Authenticatable
{
    use AuthenticatableTrait;
    use Notifiable;

    protected $fillable = [
        'img',
        'nom',
        'prenom',
        'telephone',
        'ville',
        'adresse',
        'motdepasse',
        'email',
    ];

}