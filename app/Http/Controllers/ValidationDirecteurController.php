<?php

namespace App\Http\Controllers;
use App\Models\DemandeAbsence;
use Illuminate\Http\Request;

class ValidationDirecteurController extends Controller
{
    public function index() {
    $demandes =DemandeAbsence::where('etat_chef', 'valide')
                               ->where('etat_directeur', 'en_attente')->get();
    return view('directeur.validation', compact('demandes'));
}

public function traiter(Request $request, $id) {
    $demande = DemandeAbsence::findOrFail($id);
    $demande->etat_directeur = $request->input('action');
    $demande->save();
    $user->notify(new DemandeValideeNotification($demande));


    // Ajouter une logique de notification ici aussi
    return redirect()->route('directeur.validation')->with('success', 'Demande traitée');
}

}
