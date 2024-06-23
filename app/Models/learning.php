<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class learning extends Model
{
    use HasFactory;

    protected $fillable = [
        'training',
        'capability',
        'improvement',
        'grow',
        'note',
        'id_personnel',
        'responsable',
        'id_campagne'
    ] ;
}
