<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    protected $table= 'avis';
    protected $fillable = ['demandes_id', 'Commentaire','rating'];

    public function professionnal(): BelongsTo
    {
        return $this->belongsTo(Professionnal::class);
    }


}
