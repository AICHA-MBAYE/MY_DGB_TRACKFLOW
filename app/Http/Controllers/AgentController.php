<?php

namespace App\Http\Controllers;

use App\Models\Agent; // Assurez-vous que c'est le bon modèle que vous utilisez pour les agents
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // Pour hacher les mots de passe
use Illuminate\Support\Str; // Pour générer des mots de passe aléatoires
use Illuminate\Support\Facades\Mail; // Pour l'envoi d'emails
use App\Mail\AgentValidatedMail; // Nous allons créer cette classe plus tard
use App\Models\DemandeAbsence;

class AgentController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupération de la liste des agents
        // Vous pourriez vouloir filtrer ou paginer cette liste pour des applications plus grandes
        $agents = Agent::all(); // Récupère tous les agents, y compris ceux en attente, validés, rejetés.
        // Retourner la page agents/index.blade.php avec la liste des agents
        // J'ai mis à jour le nom de la vue selon notre discussion précédente
        return view('agent.index', compact('agents'));
    }


    public function validatedIndex()
    {
        // Récupère uniquement les agents dont le statut est 'validated'
        $agents = Agent::where('status', 'validated')->get();
        return view('agent.validated-agents', compact('agents'));
    }
    /**
     * Show the form for creating a new agent (registration page for admin).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retourne la vue pour l'inscription d'un nouvel agent.
        // J'ai mis à jour le nom de la vue pour refléter le nouveau fichier 'register-agent.blade.php'
        return view('auth.register-agent');
    }

    /**
     * Store a newly created agent (registration) in storage.
     * The agent's status will be 'pending' and no password will be set yet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des champs pour l'inscription
        $this->validate($request, [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agents,email', // Assurez-vous que l'email est unique dans la table 'agents'
            'role' => [
                'required',
                Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])
            ],
            // Validation de la direction : assurez-vous que ces valeurs sont cohérentes avec votre DB ou votre liste fixe.
            // Si vos directions sont en base de données, la règle 'in' devrait être remplacée par 'exists:directions,code' (si 'code' est le champ utilisé)
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'CSS', 'CER'])
            ]
        ]);

        // Création de l'objet Agent
        $agent = Agent::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'direction' => $request->direction,
            'password' => null, // Le mot de passe est null à l'inscription, il sera défini lors de la validation
            'status' => 'pending', // Le statut par défaut est 'pending' (en attente de validation)
        ]);

        // Redirection vers la liste des agents avec un message de succès
        return redirect()->route('agent.index')->with('success', 'Agent inscrit avec succès. En attente de validation.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View
     */
    public function show(Agent $agent)
    {
        return view('agent.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View
     */
    public function edit(Agent $agent)
    {
        return view('agent.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Agent $agent)
    {
        // Validation des champs pour la mise à jour
        $this->validate($request, [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('agents')->ignore($agent->id)
            ],
            'role' => [
                'required',
                Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])
            ],
            // Attention: Votre liste 'DGC PT,DGD,DGID,DGB,DGSF' ici est différente de celle dans 'store'.
            // Assurez-vous que ces listes sont cohérentes ou proviennent de la même source (DB).
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'CSS', 'CER']) // J'ai harmonisé ici avec la liste du formulaire
            ]
        ]);

        // Mise à jour de l'objet Agent
        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'direction' => $request->direction,
            // Ne pas mettre à jour le mot de passe ou le statut via cette méthode 'update' standard,
            // car ils sont gérés par des actions d'administration spécifiques.
        ]);

        // Redirection vers la liste des agents avec un message de succès
        return redirect()->route('agent.index')->with('success', 'Agent mis à jour avec succès.');
    }

    /**
     * Valide l'inscription d'un agent, lui attribue un mot de passe et l'envoie par email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateAndAssignPassword(Request $request, Agent $agent)
    {
        // Vérifier que l'agent est en statut 'pending' avant de le valider
        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de valider.');
        }

        // Générer un mot de passe aléatoire sécurisé
        $generatedPassword = Str::random(12); // Mot de passe de 12 caractères aléatoires

        // Mettre à jour l'agent: définir le mot de passe (haché) et changer le statut à 'validated'
        $agent->update([
            'password' => Hash::make($generatedPassword), // Hacher le mot de passe avant de le sauvegarder
            'status' => 'validated',
        ]);

        // Envoyer le mot de passe à l'agent par email
        try {
            Mail::to($agent->email)->send(new AgentValidatedMail($agent, $generatedPassword));
        } catch (\Exception $e) {
            // Gérer les erreurs d'envoi d'e-mail (par exemple, logger l'erreur)
            \Log::error("Erreur lors de l'envoi de l'e-mail de validation à {$agent->email}: " . $e->getMessage());
            return redirect()->route('agent.index')->with('warning', 'Agent validé, mais l\'e-mail n\'a pas pu être envoyé.');
        }

        // Redirection avec un message de succès
        return redirect()->route('agent.index')->with('success', 'Agent validé et mot de passe envoyé par email.');
    }

    /**
     * Rejette l'inscription d'un agent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Agent $agent)
    {
        // Vérifier que l'agent est en statut 'pending' avant de le rejeter
        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de rejeter.');
        }

        // Mettre à jour le statut de l'agent à 'rejected'
        $agent->update([
            'status' => 'rejected',
            'password' => null, // Assurez-vous que le mot de passe est nullifié en cas de rejet
        ]);

        // Optionnel: Vous pouvez envoyer un email à l'agent pour l'informer du rejet.

        // Redirection avec un message de succès
        return redirect()->route('agent.index')->with('info', 'Agent rejeté avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent supprimé avec succès.');
    }
    public function downloadActe($id) {
    $demande = DemandeAbsence::findOrFail($id);
    $path = storage_path('app/'.$demande->pdf_path);
    return response()->download($path);
}
}
