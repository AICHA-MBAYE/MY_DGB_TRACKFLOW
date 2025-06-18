<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DemandeAbsence extends Model
{
    protected $fillable = [
        'user_id',
        //'type_absence_id',
        'date_debut',
        'date_fin',
        'motif',
        'statut',
        'justificatif',
        'etat_chef',
        'etat_directeur',
    ];

    // Dans App\Models\DemandeAbsence.php (ou équivalent)
public function agent()
{
    return $this->belongsTo(Agent::class, 'user_id');
}
// ...existing code...

    public function getJoursOuvresAttribute()
    {
        $start = Carbon::parse($this->date_debut);
        $end = Carbon::parse($this->date_fin);
        $joursFeries = [
            '01-01', // 1er janvier
            '05-01', // Fête du travail
            // Ajoute d'autres jours fériés ici
        ];

        $jours = 0;
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            if (!in_array($date->format('w'), [0, 6]) && !in_array($date->format('m-d'), $joursFeries)) {
                $jours++;
            }
        }
        return $jours;
    }

   /* public function typeAbsence()
    {
        return $this->belongsTo(TypeAbsence::class);
    }*/
}

