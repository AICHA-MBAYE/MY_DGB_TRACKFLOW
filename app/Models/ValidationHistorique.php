<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationHistorique extends Model
{
    use HasFactory;

    protected $table = 'validation_historiques';

    protected $fillable = [
        'agent_id',
        'demande_absence_id',
        'user_id',
        'role',
        'action',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Relation vers l'agent qui a été validé/rejeté
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    // Relation vers l'agent qui a effectué la validation (le validateur)
    public function validator()
    {
        return $this->belongsTo(Agent::class, 'user_id');
    }

    // Relation vers la demande d'absence (optionnelle)
    public function demande()
    {
        return $this->belongsTo(DemandeAbsence::class, 'demande_absence_id');
    }
}
