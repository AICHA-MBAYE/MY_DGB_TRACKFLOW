<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DemandeAbsence;
use Illuminate\Http\Request;
use App\Models\ValidationHistorique;
use Carbon\Carbon;

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
$demande->date_traitement_directeur = now(); // lors de la validation par le directeur
$demande->save();
    // Génération PDF ou autre logique ici...

    ValidationHistorique::create([
    'demande_absence_id' => $demande->id,
    'user_id' => $agent->id,
    'role' => 'directeur',
    'action' => $demande->etat_directeur, // 'acceptée' ou 'rejetée'
    'validated_at' => Carbon::now(),
]);
    return redirect()->route('directeur.validation')->with('success', 'Demande traitée');
}
public function historiqueValidations(Request $request)
{
    $historique = ValidationHistorique::where('role', 'directeur')
        ->orderBy('validated_at', 'desc')
        ->with(['demande', 'agent'])
        ->get();

    return view('directeur.historique', compact('historique'));
}
}
