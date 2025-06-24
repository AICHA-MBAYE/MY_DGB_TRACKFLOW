<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\DemandeAbsence;

class ChefServiceController extends Controller
{
    public function agents(Request $request)
    {
        // Récupère la division du chef connecté
        $division = auth()->user()->division;
        $search = $request->input('search');

        // Filtre les agents de la même division (hors chefs si besoin)
        $agents = Agent::where('division', $division)
            ->where('role', 'agent')
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('prenom', 'like', "%$search%")
                      ->orWhere('nom', 'like', "%$search%");
                });
            })
            ->get();

        return view('chef.agents', compact('agents', 'search'));
    }

    public function agentStats($id, Request $request)
{
    $agent = Agent::findOrFail($id);

    // Vérifie que l'agent appartient à la division du chef
    if ($agent->division !== auth()->user()->division) {
        abort(403, "Accès refusé.");
    }

    $annee = $request->input('annee', now()->year);
    $annees = range(2025, now()->year);

    // Ne prendre que les demandes acceptées
    $demandes = DemandeAbsence::where('user_id', $agent->id)
        ->whereYear('date_debut', $annee)
        ->where('etat_directeur', 'acceptée') // <-- Ajout du filtre
        ->get();

    $stats = [];
    foreach (range(1, 12) as $mois) {
        $duMois = $demandes->filter(function($d) use ($mois) {
            return \Carbon\Carbon::parse($d->date_debut)->month == $mois;
        });
        $stats[$mois] = [
            'nb_jours' => $duMois->sum('jours_ouvres'),
            'nb_demandes' => $duMois->count(),
        ];
    }

    return view('demande_absence.stats', [
        'agent' => $agent,
        'annee' => $annee,
        'annees' => $annees,
        'stats' => $stats,
    ]);
    }
}