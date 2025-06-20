<?php

namespace App\Http\Controllers;

use App\Models\DemandeAbsence;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DemandeAbsenceController extends Controller
{
    public function create()
{
    $demandes = DemandeAbsence::orderBy('created_at', 'desc')->get();
    return view('demande_absence.create', compact('demandes'));
}

    public function edit($id)
{
    $demande = DemandeAbsence::findOrFail($id);
    return view('demande_absence.edit', compact('demande'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'motif' => 'required|string|max:500',
        'justificatif' => 'sometimes|nullable|mimes:pdf|max:2048',
    ]);

    $demande = DemandeAbsence::findOrFail($id);
    $demande->update($request->only(['date_debut', 'date_fin', 'motif']));

    return redirect()->route('demande_absence.index')->with('success', 'Demande mise à jour.');
}

public function destroy($id)
{
    $demande = DemandeAbsence::findOrFail($id);
    $demande->delete();

    return redirect()->route('demande_absence.index')->with('success', 'Demande supprimée.');
}
public function index()
{
    $demandes = DemandeAbsence::where('user_id', auth()->id())->get();
    return view('demande_absence.index', compact('demandes'));
}

    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:500',
            'justificatif' => 'sometimes|nullable|mimes:pdf|max:2048',
        ], [
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

      /*  $aujourdHui = Carbon::today();
        if (Carbon::parse($request->date_debut)->lt($aujourdHui)) {
            return back()->withErrors([
                'date_debut' => 'La date de début ne peut pas être antérieure à aujourd\'hui.',
            ])->withInput();
        }*/

        $filePath = null;
        if ($request->hasFile('justificatif')) {
            $filePath = $request->file('justificatif')->store('justificatifs', 'public');
        }
          $aujourdhui = \Carbon\Carbon::today();
          $dateDebut = \Carbon\Carbon::parse($request->date_debut);

      if ($dateDebut->lt($aujourdhui)) {
        // Chef de service BAF
        $chef = Agent::where('division', 'BAF')->where('role', 'chef_service')->first();
        if (!$chef) {
            return back()->withErrors(['division' => 'Aucun chef de service BAF trouvé.'])->withInput();
        }
    } else {
        // Chef de la division de l'agent
        $division = auth()->user()->division;
        $chef = Agent::where('division', $division)->where('role', 'chef_service')->first();
        if (!$chef) {
            return back()->withErrors(['division' => 'Aucun chef de service trouvé pour votre division.'])->withInput();
        }
    }
        DemandeAbsence::create([
            'user_id' => Auth::id(),
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'motif' => $request->motif,
            'justificatif' => $filePath,
            'etat_chef' => 'en_attente',
            'etat_directeur' => 'en_attente',
        ]);

       return redirect()->route('demande_absence.index')->with('success', 'Demande envoyée avec succès.');

    }
    public function stats(Request $request)
{
     $userId = auth()->id();
    $annee = $request->input('annee', now()->year);

 $annees = range(2025, now()->year);

    // Nombre de jours ouvrés et de demandes par mois pour l'année sélectionnée
    $demandes = DemandeAbsence::where('user_id', $userId)
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

    return view('demande_absence.stats', compact('annee', 'annees', 'stats'));
}
public function submit($id)
{
    // Exemple : soumettre la demande d'absence
    $demande = DemandeAbsence::findOrFail($id);
    $demande->statut = 'soumise';
    $demande->save();

    return redirect()->route('demande_absence.index')->with('success', 'Demande soumise avec succès.');
}
}
