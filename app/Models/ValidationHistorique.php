<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Agent; // Ajoute ce use en haut du fichier

class ValidationHistorique extends Model
{
    protected $fillable = [
        'demande_absence_id',
        'user_id',
        'role',
        'action',
        'validated_at',
    ];

    public function demande()
    {
        return $this->belongsTo(DemandeAbsence::class, 'demande_absence_id');
    }

    public function user()
    {
        return $this->belongsTo(Agent::class, 'user_id');
    }
}
