<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class situation extends Model
{
    use HasFactory;

    protected $fillable = [
        "date_recrutement",
        "nature_acte",
        "statut_acte",
        "id_corps",
        "id_grade",
        "id_categorie",
        "nommination",
        "age_dep_retraite",
        "date_dep_retraite",
        "poste_actuel",
        "niv_instruction",
        "id_personnel"
    ];
}
