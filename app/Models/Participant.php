<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['payment_status', 'pack_taken'];

    public function payment() 
    {
        return $this->hasOne(Payment::class, 'participant_id', 'id');
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by', 'id');
    }
    
    public function getPaymentStatusAttribute()
    {
        return $this->payment ? $this->payment->status : null;
    }

    public function getPackTakenAttribute() {
        return $this->handled_by != null;
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
