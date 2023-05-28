<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//User::create(['name' => 'Samir' ,])


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'type',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Enseignant(){
        return $this->hasOne(Enseignant::class); //Un utilisateur est lié à un et un seul enseignant
    }

    public function Intervention(){
        return $this->hasMany(Intervention::class); //Un utilisateur a  un ou plussieurs interventions
    }

    public function Paiement(){
        return $this->hasOne(Paiement::class); //Un utilisateur est lié à un et un seul paiement
    }
}
