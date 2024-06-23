<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class objectif extends Model
{
    use HasFactory;

    protected $fillable = ['specifique', 'operationnel', 'indicateur', 'id_personnel', 'id_campagne', 'statut', 'valeur', 'cible', 'source_collecte', 'frequence'];
}
