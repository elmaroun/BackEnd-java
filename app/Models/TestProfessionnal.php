<?php
namespace App\Models;


use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;


class TestProfessionnal extends Professionnal implements Authenticatable
{
    use AuthenticatableTrait;
    protected $table = 'professionnals';
    
}