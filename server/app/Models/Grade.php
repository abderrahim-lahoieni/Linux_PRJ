<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'charge_statutaire',
        'taux_horaire_vacation'
    ];

    public function Enseignant(){
        return $this->hasMany(Enseignant::class);  //Un Grade peut etre liée à un ou plusieurs enseignants
    }
}
