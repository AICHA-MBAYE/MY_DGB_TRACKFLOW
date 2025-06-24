<?php

namespace App\Http\Controllers;
use App\Notifications\DemandeValideeNotification;
use App\Models\DemandeAbsence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ValidationChefController extends Controller
{
    public function index() {
     $agent = Auth::user();

    $query = DemandeAbsence::query()
        ->where('etat_chef', 'en_attente')
        ->where('statut', 'soumise');

    if ($agent->role === 'chef_service') {
        if ($agent->division === 'BAF') {
            // Chef BAF : demandes dont l'agent est BAF et même direction, date passée
            $query->whereHas('agent', function ($q) use ($agent) {
                $q->where('direction', $agent->direction);
            })->whereDate('date_debut', '<', now());
        } else {
            // Autres chefs : leur division, à venir
            $query->whereHas('agent', function ($q) use ($agent) {
                $q->where('division', $agent->division);
            })->whereDate('date_debut', '>=', now());
        }
    }

    $demandes = $query->get();
    return view('chef.validation', compact('demandes'));
}

public function traiter(Request $request, $id) {
    $demande = DemandeAbsence::findOrFail($id);
    $demande->etat_chef = $request->input('action');
    if ($request->input('action') === 'rejetée') {
        $demande->motif_rejet_chef = $request->input('motif_rejet_chef');
   $demande->etat_directeur = 'rejetée';
        $demande->motif_rejet_directeur = 'Rejet automatique suite au refus du chef de service (' . $request->input('motif_rejet_chef') . ')';
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
