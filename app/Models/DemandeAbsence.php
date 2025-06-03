<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeAbsence extends Model
{
    protected $fillable = [
        //'user_id',
        //'type_absence_id',
        'date_debut',
        'date_fin',
        'motif',
        'statut',
        'justificatif',
    ];

    // Dans App\Models\DemandeAbsence.php (ou équivalent)
public function agent()
{
    return $this->belongsTo(Agent::class, 'user_id');
}


    public function typeAbsence()
    {
        return $this->belongsTo(TypeAbsence::class);
    }
}

