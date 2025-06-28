<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANT: Utilise Authenticatable pour l'authentification
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable // Le modèle doit étendre Authenticatable pour les fonctionnalités d'authentification
{
    use HasFactory, Notifiable; // Ajout de Notifiable pour l'envoi d'emails

    /**
     * The attributes that are mass assignable.
     * Ces attributs peuvent être renseignés en masse via Agent::create() ou Agent::update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'password',
        'role',
        'direction',
        'division',
        'status',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Ces attributs ne seront pas affichés lorsque le modèle est converti en tableau ou JSON.
     * Utile pour masquer les informations sensibles comme les mots de passe.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Ajouté pour masquer le token de rappel
    ];

    /**
     * The attributes that should be cast.
     * Ces attributs seront automatiquement convertis en types de données spécifiques.
     * Ici, le mot de passe est haché automatiquement par Laravel 9+.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Si vous utilisez la vérification d'email
        'password' => 'hashed', // Hachage automatique du mot de passe
        'validated_at' => 'datetime',
        'must_change_password' =>'boolean',
    ];

    /**
     * Relation vers l'historique de validation pour cet agent
     */
    public function validationHistory()
    {
        return $this->hasMany(ValidationHistorique::class, 'agent_id');
    }

    /**
     * Relation pour récupérer le dernier validateur de l'agent
     */
    public function validatorAgent()
    {
        return $this->hasOne(ValidationHistorique::class, 'agent_id')
                    ->where('action', 'validated')
                    ->latest('validated_at');
    }
}
