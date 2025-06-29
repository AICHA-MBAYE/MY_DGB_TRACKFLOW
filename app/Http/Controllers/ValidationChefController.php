<?php

namespace App\Http\Controllers;
use App\Notifications\DemandeValideeNotification;
use App\Models\DemandeAbsence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ValidationHistorique;
use Carbon\Carbon;

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
        $demande->motif_rejet_directeur = 'Rejet  suite au refus du chef de service ';
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
$demande->date_traitement_chef = now(); // lors de la validation par le chef
$demande->save();

ValidationHistorique::create([
    'demande_absence_id' => $demande->id,
    'user_id' => $agent->id,
    'role' => 'chef_service', // ou 'chef_service' selon ta logique
    'action' => $demande->etat_chef, // 'acceptée' ou 'rejetée'
    'validated_at' => Carbon::now(),
]);

    return redirect()->route('chef.validation')->with('success', 'Demande traitée');
}

}
