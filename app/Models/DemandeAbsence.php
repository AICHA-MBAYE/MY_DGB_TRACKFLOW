<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Assurez-vous que Carbon est importé
use App\Models\Agent;

class DemandeAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_debut',
        'date_fin',
        'motif',
        'justificatif',
        'etat_chef',
        'motif_rejet_chef',
        'etat_directeur',
        'motif_rejet_directeur',
        'pdf_path',
        'date_traitement_chef',
        'date_traitement_directeur',
        'statut',
    ];

    // C'est crucial : assurez-vous que ces champs sont castés en objets Carbon
    protected $dates = [
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',
        'date_traitement_chef',
        'date_traitement_directeur',
    ];

    /**
     * Accesseur pour calculer la durée de l'absence en jours ouvrables (hors week-ends et jours fériés).
     *
     * @return int La durée en jours ouvrables.
     */
    public function getDureeJoursOuvrablesAttribute(): int
    {
        if (!$this->date_debut || !$this->date_fin) {
            return 0;
        }

        $startDate = Carbon::parse($this->date_debut);
        $endDate = Carbon::parse($this->date_fin);
        $workingDays = 0;

        // Clone la date de début pour ne pas modifier l'originale
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Vérifie si le jour n'est ni un samedi (6) ni un dimanche (0)
            if (!$currentDate->isWeekend()) {
                // Vérifie si ce n'est pas un jour férié
                if (!$this->isPublicHoliday($currentDate)) {
                    $workingDays++;
                }
            }
            $currentDate->addDay(); // Passe au jour suivant
        }

        return $workingDays;
    }

    /**
     * Vérifie si une date donnée est un jour férié au Sénégal.
     * Cette liste doit être maintenue manuellement ou via une source externe.
     *
     * @param Carbon $date
     * @return bool
     */
    protected function isPublicHoliday(Carbon $date): bool
    {
        // IMPORTANT : Cette liste est un exemple et doit être mise à jour
        // avec les jours fériés réels du Sénégal pour l'année en cours et les années futures.
        // Les jours fériés peuvent changer d'année en année.
        // Pour 2025 (année actuelle au moment de cette réponse), voici quelques exemples :
        $publicHolidays = [
            // Jours fériés fixes (jour/mois)
            '01-01', // Jour de l'An
            '04-04', // Fête de l'Indépendance
            '01-05', // Fête du Travail
            '15-08', // Assomption
            '01-11', // Toussaint
            '25-12', // Noël


        ];

        // Format de la date pour la comparaison (jour-mois ou année-mois-jour)
        $formattedDateDM = $date->format('d-m');
        $formattedDateYMD = $date->format('Y-m-d');

        // Vérifie si la date est dans la liste des jours fériés
        return in_array($formattedDateDM, $publicHolidays) || in_array($formattedDateYMD, $publicHolidays);
    }

    // ... le reste de votre modèle ...

    // Relation avec l'utilisateur (agent)
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'user_id');
    }
}
