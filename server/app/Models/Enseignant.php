<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Etablissement;
use App\Models\Grade;

class Enseignant extends Model
{
    use HasFactory;
    protected $fillable = [
        'ppr',
        'nom',
        'prenom',
        'date_naissance',
        'etablissement',
        'id_grade',
        'id_user',
        'etat'
    ];


    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class); 
    }

    public function Grade()
    {
        return $this->belongsTo(Grade::class); 
    }
    
    public function User()
    {
        return $this->belongsTo(User::class); 
    }

}