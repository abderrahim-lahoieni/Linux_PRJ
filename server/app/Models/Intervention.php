<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;
    protected $fillable = [
        'intitule_intervention',
        'annee__univ',
        'semestre',
        'date_debut',
        'date_fin',
        'nbr_heures',
        'enseignant_id',
        'etablissement_id',
        'visa_etb',
        'visa_uae'
    ];
}
