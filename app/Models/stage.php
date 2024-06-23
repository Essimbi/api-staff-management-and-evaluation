<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stage extends Model
{
    use HasFactory;

    protected $fillable = [
        "theme_stage",
        "domaine",
        "institut",
        "nbre_jour",
        "localisation",
        "id_personnel"
    ];
}
