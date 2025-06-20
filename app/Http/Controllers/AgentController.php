<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentValidatedMail;
use App\Mail\AgentRejectedMail;
use Illuminate\Support\Facades\Auth;
use App\Models\DemandeAbsence;

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
     * Méthode d'aide pour filtrer les agents par division pour des rôles spécifiques.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterAgentsByDivision($query)
    {
        $user = Auth::user();
        if ($user && $user->role === 'chef_service') {
            return $query->where('division', $user->division);
        }
        return $query;
    }

    /**
     * Affiche la liste de tous les agents (pour l'administration, inclut les en attente).
     * Filtré par direction pour admin_sectoriel et par division pour chef_service.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Agent::where('status', 'pending');

        if (Auth::user()->role === 'admin_sectoriel') {
            $query = $this->filterAgentsByDirection($query);
        }
        if (Auth::user()->role === 'chef_service') {
            $query = $this->filterAgentsByDivision($query);
        }

        $agents = $query->get();
        return view('agent.index', compact('agents'));
    }

    /**
     * Affiche la liste uniquement des agents validés.
     * Filtré par direction pour chef_service et directeur, et exclut super_admin pour admin_sectoriel.
     *
     * @return \Illuminate\View\View
     */
    public function validatedIndex()
    {
        $query = Agent::where('status', 'validated');

        if (in_array(Auth::user()->role, ['chef_service', 'directeur', 'admin_sectoriel'])) {
             $query = $this->filterAgentsByDirection($query);
        }
        if (Auth::user()->role === 'chef_service') {
            $query = $this->filterAgentsByDivision($query);
        }
        // Exclure les super administrateurs pour les admin sectoriels
        if (Auth::user()->role === 'admin_sectoriel') {
            $query->where('role', '!=', 'super_admin');
        }

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
     * Le rôle sera null (assigné par l'admin), le statut 'pending'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agents,email',
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'DCB', 'DODP', 'DS', 'DP', 'DMTA', 'CSS', 'CER'])
            ],
            'division' => 'required|string|max:255',
        ]);

        $agent = Agent::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => null,
            'direction' => $request->direction,
            'division' => $request->division,
            'password' => null,
            'status' => 'pending',
        ]);

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
        if ($user->role === 'agent' && $user->id !== $agent->id) {
             return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent.');
        }
        if (in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur']) && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent car il ne fait pas partie de votre direction.');
        }
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
                'nullable',
                Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])
            ],
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'DCB', 'DORDP', 'DS', 'DP', 'DMTA', 'CSS', 'CER'])
            ],
            'division' => 'nullable|string|max:255',
        ]);

        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'direction' => $request->direction,
            'division' => $request->division,
        ]);

        return redirect()->route('agent.index')->with('success', 'Agent mis à jour avec succès.');
    }

    public function assignRole(Request $request, Agent $agent)
    {
        $request->validate([
            'role' => ['required', Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])]
        ]);
        $agent->role = $request->role;

        if (in_array($request->role, ['super_admin', 'admin_sectoriel', 'directeur'])) {
        $agent->division = null;
       }

        $agent->save();

        // Redirige vers la page précédente
        return back()->with('success', 'Rôle assigné avec succès.');
    }

    public function validateAndAssignPassword(Request $request, Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation : seuls les super_admin et admin_sectoriel peuvent valider et assigner un mot de passe
        if (!in_array($user->role, ['super_admin', 'admin_sectoriel'])) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider des agents ou à leur assigner des rôles.');
        }

        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider cet agent car il ne fait pas partie de votre direction.');
        }

        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de valider.');
        }

        $rules = [
            'role_to_assign' => [
                'required',
                Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])
            ],
        ];

        if ($user->role === 'admin_sectoriel') {
            $rules['role_to_assign'][] = Rule::notIn(['super_admin', 'admin_sectoriel']);
        }
        $request->validate($rules);

        $generatedPassword = Str::random(12);

        // Préparer les données pour la mise à jour
        $updateData = [
            'password' => Hash::make($generatedPassword),
            'status' => 'validated',
            'role' => $request->role_to_assign, // Attribution du rôle
            'must_change_password' => true, // Force le changement de mot de passe à la première connexion
        ];

        // Si le rôle assigné est 'super_admin', 'admin_sectoriel', ou 'directeur',
        // la division de l'agent est mise à null.
        if (in_array($request->role_to_assign, ['super_admin', 'admin_sectoriel', 'directeur'])) {
            $updateData['division'] = null;
        }

        $agent->update($updateData); // Utilise les données préparées pour la mise à jour

        try {
            Mail::to($agent->email)->send(new AgentValidatedMail($agent, $generatedPassword));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'envoi de l'e-mail de validation à {$agent->email}: " . $e->getMessage());
            return redirect()->route('agent.index')->with('warning', 'Agent validé, mais l\'e-mail n\'a pas pu être envoyé.');
        }

        return redirect()->route('agent.index')->with('success', 'Agent validé et mot de passe et rôle assignés.');
    }

    public function showChangePasswordForm()
    {
        return view('agent.change-password');
    }

    public function changePassword(Request $request)
    {
       $request->validate([
        'password' => 'required|confirmed|min:8',
    ]);

    $user = Auth::user();
    $user->password = bcrypt($request->password);
    $user->must_change_password = false; // si tu utilises ce champ
    $user->save();

    Auth::logout();

    return redirect()->route('login')->with('success', 'Connectez-vous avec votre nouveau mot de passe.');
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
        $user = Auth::user();

        // Vérification d'autorisation : seuls les super_admin et admin_sectoriel peuvent rejeter un agent.
        if (!in_array($user->role, ['super_admin', 'admin_sectoriel'])) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter des agents.');
        }

        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter cet agent car il ne fait pas partie de votre direction.');
        }

        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de rejeter.');
        }

        $agent->update([
            'status' => 'rejected',
            'password' => null,
            'role' => null,
        ]);

        try {
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
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation : seuls les super_admin et admin_sectoriel peuvent supprimer un agent.
        if (!in_array($user->role, ['super_admin', 'admin_sectoriel'])) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer des agents.');
        }

        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cet agent car il ne fait pas partie de votre direction.');
        }

        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent supprimé avec succès.');
    }

    /**
     * Affiche le formulaire pour permettre à un agent rejeté de modifier son inscription.
     *
     * @param  \App\Models\Agent  $agent L'instance de l'agent à modifier.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editRejectedForm(Agent $agent)
    {
        if ($agent->status !== 'rejected') {
            return redirect()->route('welcome')->with('error', 'Accès non autorisé ou demande déjà traitée.');
        }
        return view('auth.edit-rejected-agent', compact('agent'));
    }

    /**
     * Met à jour l'inscription d'un agent rejeté et change son statut en 'pending'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent L'instance de l'agent à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRejectedRegistration(Request $request, Agent $agent)
    {
        if ($agent->status !== 'rejected') {
            return redirect()->route('welcome')->with('error', 'Mise à jour non autorisée ou demande déjà traitée.');
        }

        $this->validate($request, [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('agents')->ignore($agent->id),
            ],
            'direction' => [
                'required',
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'DCB', 'DORDP', 'DS', 'DP', 'DMTA', 'CSS', 'CER'])
            ],
            'division' => 'required|string|max:255',
        ]);

        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => null,
            'direction' => $request->direction,
            'division' => $request->division,
            'status' => 'pending',
        ]);

        return redirect()->route('welcome')->with('success', 'Votre inscription a été mise à jour et soumise à nouveau. Elle est en attente de validation par l\'administrateur sectoriel.');
    }

    /**
     * Télécharge un acte lié à une demande d'absence.
     * Assurez-vous que DemandeAbsence est bien importé en haut du fichier.
     *
     * @param  int  $id L'ID de la demande d'absence.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadActe($id)
    {
        // Importation du modèle DemandeAbsence au besoin
        // use App\Models\DemandeAbsence;
        $demande = \App\Models\DemandeAbsence::findOrFail($id); // Utilisation complète du namespace
        $path = storage_path('app/' . $demande->pdf_path);
        return response()->download($path);
    }

}
