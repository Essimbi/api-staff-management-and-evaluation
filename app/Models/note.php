<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    use HasFactory;

    protected $fillable = ['valeur', 'observation', 'id_objectif', 'id_personnel', 'id_campagne', "responsable"] ;
}
