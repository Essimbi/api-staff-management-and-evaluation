<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qualite extends Model
{
    use HasFactory;

    protected $fillable = ['creativite', 'esprit_equipe', 'adaptation', 'relation', 'communication', 'id_personnel', 'id_campagne', "responsable"] ;
}
