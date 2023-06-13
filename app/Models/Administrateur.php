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
        'etablissement',
        'id_user',
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
