<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Authenticatable
{
    use HasFactory;
// définition des attributs que l'utilisateur a le droit de renseigner dans la BD
protected $fillable = [
'prenom',
'nom',
'email',
'role',
'direction',
];
}
