<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // Pour hacher les mots de passe
use Illuminate\Support\Str; // Pour générer des mots de passe aléatoires
use Illuminate\Support\Facades\Mail; // Pour l'envoi d'emails
use App\Mail\AgentValidatedMail; // Nous allons créer cette classe plus tard
use App\Models\DemandeAbsence;
use App\Mail\AgentRejectedMail; // Importez la classe Mailable pour le rejet
use Illuminate\Support\Facades\Auth; // Pour accéder à l'utilisateur authentifié

class AgentController extends Controller
{
    use ValidatesRequests;

    /**
     * Méthode d'aide pour filtrer les agents par direction pour des rôles spécifiques.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterAgentsByDirection($query)
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur'])) {
            return $query->where('direction', $user->direction);
        }
        return $query;
    }

    /**
     * Affiche la liste de tous les agents (pour l'administration, inclut les en attente).
     * Filtré par direction pour admin_sectoriel.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Agent::query();

        // L'administrateur sectoriel ne voit que les agents de sa direction
        if (Auth::user()->role === 'admin_sectoriel') {
            $query = $this->filterAgentsByDirection($query);
        }
        // Le super administrateur voit tous les agents (pas de filtre nécessaire ici)

        $agents = $query->get();
        return view('agent.index', compact('agents'));
    }

    /**
     * Affiche la liste uniquement des agents validés.
     * Filtré par direction pour chef_service et directeur.
     *
     * @return \Illuminate\View\View
     */
    public function validatedIndex()
    {
        $query = Agent::where('status', 'validated');

        // Le chef de service et le directeur ne voient que les agents validés de leur direction
        // L'administrateur sectoriel voit aussi les agents validés de sa direction
        if (in_array(Auth::user()->role, ['chef_service', 'directeur', 'admin_sectoriel'])) {
             $query = $this->filterAgentsByDirection($query);
        }
        // Le super administrateur voit tous les agents validés

        $agents = $query->get();
        return view('agent.validated-agents', compact('agents'));
    }

    /**
     * Affiche le formulaire pour créer un nouvel agent (page d'inscription).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register-agent');
    }

    /**
     * Enregistre un nouvel agent (inscription) dans la base de données.
     * Le statut de l'agent sera 'pending' et aucun mot de passe ne sera défini initialement.
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
            'password' => null, // Le mot de passe est null à l'inscription initiale
            'status' => 'pending', // Le statut par défaut est 'pending' (en attente de validation)
        ]);

        // Redirection vers la page d'accueil avec un message de succès spécifique
        return redirect()->route('welcome')->with('success', 'Cher agent, votre inscription a bien été enregistrée. Pour accéder à l\'application, vous devez être approuvé par votre administrateur sectoriel. Veuillez vérifier vos mails pour suivre la suite de votre procédure.');
    }

    /**
     * Affiche la ressource spécifiée.
     * Ajout d'une vérification d'autorisation pour les agents d'une direction spécifique.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Agent $agent)
    {
        $user = Auth::user();
        // Permet à un agent de voir son propre profil
        // Permet aux administrateurs de voir les profils des agents de leur direction ou tous pour super_admin
        if ($user->role === 'agent' && $user->id !== $agent->id) {
             return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent.');
        }
        if (in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur']) && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent car il ne fait pas partie de votre direction.');
        }
        // Super admin peut voir n'importe qui

        return view('agent.show', compact('agent'));
    }

    /**
     * Affiche le formulaire pour éditer la ressource spécifiée.
     * Restriction: admin_sectoriel ne peut éditer que les agents de sa direction.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation pour admin_sectoriel
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->route('agent.index')->with('error', 'Vous n\'êtes pas autorisé à éditer cet agent car il ne fait pas partie de votre direction.');
        }
        return view('agent.edit', compact('agent'));
    }

    /**
     * Met à jour la ressource spécifiée dans la base de données.
     * Restriction: admin_sectoriel ne peut mettre à jour que les agents de sa direction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation pour admin_sectoriel
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->route('agent.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cet agent car il ne fait pas partie de votre direction.');
        }

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
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'CSS', 'CER'])
            ]
        ]);

        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'direction' => $request->direction,
        ]);

        return redirect()->route('agent.index')->with('success', 'Agent mis à jour avec succès.');
    }

    /**
     * Valide l'inscription d'un agent, lui attribue un mot de passe et l'envoie par email.
     * Restriction: admin_sectoriel ne peut valider que les agents de sa direction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateAndAssignPassword(Request $request, Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation pour admin_sectoriel
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider cet agent car il ne fait pas partie de votre direction.');
        }

        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de valider.');
        }

        $generatedPassword = Str::random(12);

        $agent->update([
            'password' => Hash::make($generatedPassword),
            'status' => 'validated',
        ]);

        try {
            Mail::to($agent->email)->send(new AgentValidatedMail($agent, $generatedPassword));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'envoi de l'e-mail de validation à {$agent->email}: " . $e->getMessage());
            return redirect()->route('agent.index')->with('warning', 'Agent validé, mais l\'e-mail n\'a pas pu être envoyé.');
        }

        return redirect()->route('agent.index')->with('success', 'Agent validé et mot de passe envoyé par email.');
    }

    /**
     * Rejette l'inscription d'un agent.
     * Restriction: admin_sectoriel ne peut rejeter que les agents de sa direction.
     * Envoie un email à l'agent pour l'informer du rejet et lui proposer de modifier son inscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation pour admin_sectoriel
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter cet agent car il ne fait pas partie de votre direction.');
        }

        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de rejeter.');
        }

        // Mettre à jour le statut de l'agent à 'rejected'
        $agent->update([
            'status' => 'rejected',
            'password' => null, // Assurez-vous que le mot de passe est nullifié en cas de rejet
        ]);

        // Envoyer l'email de rejet à l'agent
        try {
            // Vous pouvez passer une raison de rejet si vous avez un champ pour cela dans le formulaire de rejet
            $rejectionReason = $request->input('rejection_reason', 'Une incohérence a été détectée lors de la vérification de vos données. Veuillez corriger vos informations.');
            Mail::to($agent->email)->send(new AgentRejectedMail($agent, $rejectionReason));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'envoi de l'e-mail de rejet à {$agent->email}: " . $e->getMessage());
            return redirect()->route('agent.index')->with('warning', 'Agent rejeté, mais l\'e-mail de notification n\'a pas pu être envoyé.');
        }

        return redirect()->route('agent.index')->with('info', 'Agent rejeté avec succès et email de notification envoyé.');
    }

    /**
     * Supprime la ressource spécifiée de la base de données.
     * Restriction: admin_sectoriel ne peut supprimer que les agents de sa direction.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation pour admin_sectoriel
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cet agent car il ne fait pas partie de votre direction.');
        }

        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent supprimé avec succès.');
    }

    /**
     * Affiche le formulaire pour permettre à un agent rejeté de modifier son inscription.
     * Cette route n'est pas protégée par le middleware 'auth' car l'agent n'est pas encore connecté.
     *
     * @param  \App\Models\Agent  $agent L'instance de l'agent à modifier.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editRejectedForm(Agent $agent)
    {
        // On s'assure que l'agent existe et que son statut est bien 'rejected'
        if ($agent->status !== 'rejected') {
            // Redirige vers la page d'accueil avec une erreur si le statut n'est pas 'rejected'
            return redirect()->route('welcome')->with('error', 'Accès non autorisé ou demande déjà traitée.');
        }
        // Affiche le formulaire de modification avec les données de l'agent
        return view('auth.edit-rejected-agent', compact('agent'));
    }

    /**
     * Met à jour l'inscription d'un agent rejeté et change son statut en 'pending'.
     * Cette route n'est pas protégée par le middleware 'auth'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent L'instance de l'agent à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadActe($id)
{
    $demande = DemandeAbsence::findOrFail($id);
    $path = storage_path('app/' . $demande->pdf_path);
    return response()->download($path);
}
    public function updateRejectedRegistration(Request $request, Agent $agent)
    {
        // On s'assure que l'agent existe et que son statut est bien 'rejected'
        if ($agent->status !== 'rejected') {
            // Redirige vers la page d'accueil avec une erreur si le statut n'est pas 'rejected'
            return redirect()->route('welcome')->with('error', 'Mise à jour non autorisée ou demande déjà traitée.');
        }

        $this->validate($request, [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            // L'email doit être unique SAUF pour l'agent en cours de modification
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('agents')->ignore($agent->id),
            ],
            'role' => [
                'required',
                Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])
            ],
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'CSS', 'CER'])
            ]
        ]);

        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'direction' => $request->direction,
            'status' => 'pending', // Remet le statut à 'pending' pour une nouvelle validation
        ]);

        return redirect()->route('welcome')->with('success', 'Votre inscription a été mise à jour et soumise à nouveau. Elle est en attente de validation par l\'administrateur sectoriel.');
    }
}
