<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clean extends Model
{
    use HasFactory;

    protected $fillable = [
        'office',
        'cars',
        'employees',
        'rewar',
        'note',
        'id_personnel',
        'responsable',
        'id_campagne'
    ];
}
