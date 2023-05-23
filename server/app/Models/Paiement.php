<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'vh',
        'taux_h',
        'brut',
        'ir',
        'net',
        'annee_univ',
        'semestre',
        'enseignant_id',
        'etablissement_id'
    ];
}
