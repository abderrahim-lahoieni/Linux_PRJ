<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;
    protected $fillable = [
        'intitule_intervention',
        'annee_univ',
        'semestre',
        'date_debut',
        'date_fin',
        'nbr_heures',
        'id_intervenant',
        'id_etab',
        'visa_etb',
        'visa_uae'
    ];

    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class); 
    }

    public function User()
    {
        return $this->belongsTo(User::class); 
    }

}
