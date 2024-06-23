<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class living extends Model
{
    use HasFactory;

    protected $fillable = [
        'integrity',
        'courage',
        'creativity',
        'value',
        'note',
        'id_personnel',
        'responsable',
        'id_campagne'
    ];
}
