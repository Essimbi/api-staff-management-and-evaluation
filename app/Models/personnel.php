<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        "matricule_perso",
        "nom_perso",
        "sexe_perso",
        "prenom_perso",
        "lieu_nais",
        "statut_matrimonial",
        "date_nais",
        "nbre_enfant",
        "position_gest",
        "motif_sortie",
        "date_sortie",
        "structure_perso",
        "structure_rattachee",
        "id_sg",
        "id_type",
        "id_arrond_origine",
        "id_arrond_travail",
        // "n1",
        // "n2",
        "anciennete",
        "date_embauche",
        "payroll",
        "direction",
        "fonctionS",
        "level",
        "lieu_travail",
        "categorie"
    ];
}
