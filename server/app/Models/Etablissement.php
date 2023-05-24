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
        'num_tel',
        'faxe',
        'ville',
        'nbre_enseignant'
    ];

    public function Enseignant(){
        return $this->hasMany(Enseignant::class); //Une établissement est liée à un ou plusieurs enseignants
    }
}
