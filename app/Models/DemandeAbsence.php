<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeAbsence extends Model
{
    protected $fillable = [
        'user_id',
        'type_absence_id',
        'date_debut',
        'date_fin',
        'description',
        'statut',
        'justificatif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeAbsence()
    {
        return $this->belongsTo(TypeAbsence::class);
    }
}

