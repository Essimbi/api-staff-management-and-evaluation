<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $fillable = ['nom'] ;

    public function comptes()
    {
        return $this->belongsToMany(compte::class) ;
    }
}
