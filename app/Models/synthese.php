<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class synthese extends Model
{
    use HasFactory;

    protected $fillable = ['critere', 'note', 'note_max', 'poids', 'score', 'id_personnel', 'id_campagne',  "responsable"] ;
}
