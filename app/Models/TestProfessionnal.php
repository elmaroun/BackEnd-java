<?php
namespace App\Models;


use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;



class TestProfessionnal extends Professionnal implements Authenticatable
{
    use AuthenticatableTrait;
    use Notifiable;
    protected $table = 'professionnals';

    
}