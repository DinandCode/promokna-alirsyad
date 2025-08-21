<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = ['to', 'subject', 'content', 'status', 'reply_for'];

    public function message()
    {
        return $this->belongsTo(Message::class, 'reply_for');
    }
}
