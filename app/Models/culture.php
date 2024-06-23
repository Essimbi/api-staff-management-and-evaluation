<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class culture extends Model
{
    use HasFactory;

    protected $fillable = [
        'strong',
        'best',
        'team',
        'diversity',
        'rewar',
        'emotional',
        'note',
        'id_personnel',
        'responsable',
        'id_campagne'
    ];
}
