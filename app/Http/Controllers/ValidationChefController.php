<?php

namespace App\Http\Controllers;
use App\Notifications\DemandeValideeNotification;
use App\Models\DemandeAbsence;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ValidationChefController extends Controller
{
    public function index() {
    $demandes = DemandeAbsence::where('etat_chef', 'en_attente')->get();
    return view('chef.validation', compact('demandes'));
}

public function traiter(Request $request, $id) {
    $demande = DemandeAbsence::findOrFail($id);
    $demande->etat_chef = $request->input('action');
    if ($request->input('action') === 'rejetée') {
        $demande->motif_rejet_chef = $request->input('motif_rejet_chef');
    } else {
        $demande->motif_rejet_chef = null;
    }
    $demande->save();

    $agent = $demande->agent;
$pdf = Pdf::loadView('acte_administratif', [
    'demande' => $demande,
    'agent' => $agent,
]);
$pdfPath = 'actes/acte_'.$demande->id.'.pdf';
$pdf->save(storage_path('app/'.$pdfPath));
$demande->pdf_path = $pdfPath;
$demande->save();

    return redirect()->route('chef.validation')->with('success', 'Demande traitée');
}

}
