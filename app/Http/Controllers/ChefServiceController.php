<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\DemandeAbsence;

class ChefServiceController extends Controller
{
    public function agents(Request $request)
{
    $search = $request->input('search');
    // À adapter selon ta structure : ici on suppose que les agents ont un champ direction ou chef_service_id
    $agents = Agent::when($search, function($query, $search) {
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
    $annee = $request->input('annee', now()->year);

    $annees = DemandeAbsence::where('user_id', $agent->id)
        ->selectRaw('YEAR(date_debut) as annee')
        ->distinct()
        ->pluck('annee');

    $demandes = DemandeAbsence::where('user_id', $agent->id)
        ->whereYear('date_debut', $annee)
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
