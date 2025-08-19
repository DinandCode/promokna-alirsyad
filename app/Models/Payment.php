<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [
        'id'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id', 'id');
    }
}
