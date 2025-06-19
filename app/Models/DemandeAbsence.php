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
        '01-01', // Jour de l’An
        '04-04', // Fête de l’Indépendance
        '05-01', // Fête du Travail
        '08-15', // Assomption
        '11-01', // Toussaint
        '12-25', // Noël
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

