<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'name',
        'price',
        'quota',
        'max_tries',
        'bib_prefix',
        'type_match',
        'last_bib'
    ];
}
