<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class concerne extends Model
{
    use HasFactory;

    protected $fillable = ['score', 'score_final', 'appreciation', 'id_campagne', 'id_personnel', 'approuved', 'n1', 'n2'];
}
