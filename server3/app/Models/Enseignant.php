<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;
    protected $fillable = [
        'ppr',
        'nom',
        'prenom',
        'date_naissance',
        'etablissement_id',
        'grade_id',
        'user_id'
    ];
}
