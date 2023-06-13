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
        'id_intervenant',
        'id_etab'
    ];

    protected $casts = [
        'vh' => 'integer',
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
