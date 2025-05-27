<?php

namespace App\Models;

use App\Observers\DemandeSubject;

use Illuminate\Database\Eloquent\Model;
use App\Observers\DemandeObserver;
use App\Observers\EmailObserver;

class Demande extends Model
{
    protected $fillable = [
        'client_id',
        'latitude',
        'longitude',
        'professionnal_id',
        'location',
        'description',
        'date',
        'statut',
        'Title',
        'Service',
    ];
    private DemandeSubject $subject;
    

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function professional()
    {
        return $this->belongsTo(TestProfessionnal::class, 'professionnal_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    //////////////////////////
    protected $observer;

    public function attachObserver(DemandeObserver $observer)
    {
        $this->observer = $observer;
    }

    public function updateStatus(string $newStatus)
    {
        $oldStatus = $this->statut;
        $this->statut = $newStatus;
        $this->save();
        if($newStatus=='Done'){
             $this->observer->notifyDoneService($newStatus);

        }else{
            if ($this->observer) {
                $this->observer->notifyStatusChange($newStatus);
            }
        }
    }
}
