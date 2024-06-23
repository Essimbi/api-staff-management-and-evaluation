<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nommination extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_rang",
        "id_nh",
        "fonction",
        "ref_acte",
        "date_nommination",
        "id_personnel"
    ] ;
}
