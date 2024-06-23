<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class diplome extends Model
{
    use HasFactory;

    protected $fillable = [
        "libelle",
        "date_optention",
        "domaine",
        "etablissement",
        "option",
        "id_ville",
        "id_pays",
        "statut",
        "id_personnel"
    ];
}
