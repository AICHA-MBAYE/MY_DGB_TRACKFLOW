<?php

namespace App\Http\Controllers;



use App\Models\DemandeAbsence;
use App\Models\TypeAbsence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeAbsenceController extends Controller
{
    public function create()
    {
        $types = TypeAbsence::all();
        return view('demande_absence.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_absence_id' => 'required|exists:type_absences,id',
            'description' => 'nullable|string',
            'justificatif' => 'nullable|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('justificatif')) {
            $filePath = $request->file('justificatif')->store('justificatifs', 'public');
        }

        DemandeAbsence::create([
            'user_id' => Auth::id(),
            'type_absence_id' => $request->type_absence_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'description' => $request->description,
            'justificatif' => $filePath,
            'statut' => 'en attente',
        ]);

        return redirect()->back()->with('success', 'Demande envoyée avec succès.');
    }
}
