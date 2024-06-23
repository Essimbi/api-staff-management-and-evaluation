<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recommandation extends Model
{
    use HasFactory;

    protected $fillable = ['valeur', 'id_personnel', 'id_campagne', 'responsable', 'fonction'];
}
