<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DemandeAbsence;
use Illuminate\Http\Request;

class ValidationDirecteurController extends Controller
{
    public function index() {
    $demandes =DemandeAbsence::where('etat_chef', 'acceptée')
                               ->where('etat_directeur', 'en_attente')->get();
    return view('directeur.validation', compact('demandes'));
}

public function traiter(Request $request, $id) {
    $demande = DemandeAbsence::findOrFail($id);
    $demande->etat_directeur = $request->input('action');
    if ($request->input('action') === 'rejetée') {
        $demande->motif_rejet_directeur = $request->input('motif_rejet_directeur');
    } else {
        $demande->motif_rejet_directeur = null;
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
    // Génération PDF ou autre logique ici...

    return redirect()->route('directeur.validation')->with('success', 'Demande traitée');
}

}
