<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class campagne extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'description', 'auto_eval', 'eval', 'ajustement', 'date_debut', 'date_fin', 'statut'] ;
}
