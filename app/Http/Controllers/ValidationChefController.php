<?php

namespace App\Http\Controllers;
use App\Models\DemandeAbsence;
use Illuminate\Http\Request;

class ValidationChefController extends Controller
{
    public function index() {
    $demandes = DemandeAbsence::where('etat_chef', 'en_attente')->get();
    return view('chef.validation', compact('demandes'));
}

public function traiter(Request $request, $id) {
    $demande =DemandeAbsence::findOrFail($id);
    $demande->etat_chef = $request->input('action');
    $demande->save();
    $user->notify(new DemandeValideeNotification($demande));


    // Ajouter une logique de notification ici
    return redirect()->route('chef.validation')->with('success', 'Demande traitée');
}

}
