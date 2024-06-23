<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commentaire extends Model
{
    use HasFactory;

    protected $fillable = ['commentaire', 'id_personnel', 'id_campagne', 'n1', 'n2'] ;
}
