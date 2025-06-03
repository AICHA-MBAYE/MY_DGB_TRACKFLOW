<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'prenom',
        'nom',
        'email',
    ];

    // Cache la colonne password même si elle n'existe pas
    protected $hidden = [
        'remember_token',
    ];

    // Désactive le mot de passe
    public function getAuthPassword()
    {
        return null;
    }
}
