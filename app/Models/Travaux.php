<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Travaux extends Model
{
    protected $table= 'travaux';
    protected $fillable = ['professionnal_id', 'description','type'];

    public function professionnal(): BelongsTo
    {
        return $this->belongsTo(Professionnal::class);
    }


}
