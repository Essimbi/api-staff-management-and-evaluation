<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;

    protected $fillable = ['q1_a', 'q1_b', 'q2', 'q3', 'id_campagne', 'id_personnel'] ;
}
