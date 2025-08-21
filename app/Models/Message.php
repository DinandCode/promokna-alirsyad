<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function replyLog()
    {
        return $this->hasOne(MailLog::class, 'reply_for');
    }
}
