<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
// définition des attributs que l'utilisateur a le droit de renseigner dans la BD
protected $fillable = [
'prenom',
'nom',
'email',
'role',
];
}
