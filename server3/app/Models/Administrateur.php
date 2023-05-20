<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    use HasFactory;
    protected $fillable = [
        'ppr',
        'nom',
        'prenom',
        'etablissement_id',
        'user_id',
    ];
    
}
