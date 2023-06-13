<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'nom',
        'telephone',
        'faxe',
        'ville',
        'nbr_enseignants',
        'etat'
    ];

    public function Enseignant(){

        return $this->hasMany(Enseignant::class); //Une établissement est liée à un ou plusieurs enseignants
        
    }

    public function Administrateur(){

        return $this->hasOne(Administrateur::class); //Une établissement est liée à un et un seul administrateur  
    }

    public function Intervention(){

        return $this->hasMany(Intervention::class); //Une établissement est liée à un ou plusieurs interventions
        
    }

    public function Paiement(){

        return $this->hasMany(Paiement::class); //Une établissement est liée à un ou plusieurs paiements
        
    }

}
