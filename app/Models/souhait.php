<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class souhait extends Model
{
    use HasFactory;

    protected $fillable = ['souhait', 'id_personnel', 'id_campagne'] ;
}
