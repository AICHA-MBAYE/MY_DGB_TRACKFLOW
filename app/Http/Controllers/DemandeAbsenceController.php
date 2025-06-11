<?php

namespace App\Http\Controllers;

use App\Models\DemandeAbsence;
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
    $demandes = DemandeAbsence::all(); // ou `where('user_id', ...)` si restriction
    return view('demande_absence.index', compact('demandes'));
}

    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:500',
            'justificatif' => 'sometimes|nullable|mimes:pdf|max:2048',
        ]);

        $aujourdHui = Carbon::today();
        if (Carbon::parse($request->date_debut)->lt($aujourdHui)) {
            return back()->withErrors([
                'date_debut' => 'La date de début ne peut pas être antérieure à aujourd\'hui.',
            ])->withInput();
        }

        $filePath = null;
        if ($request->hasFile('justificatif')) {
            $filePath = $request->file('justificatif')->store('justificatifs', 'public');
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
}
