<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\ValidationHistorique; // Importez le nouveau modèle
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

    protected function filterAgentsByDirection($query)
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur'])) {
            return $query->where('direction', $user->direction);
        }
        return $query;
    }

    protected function filterAgentsByDivision($query)
    {
        $user = Auth::user();
        if ($user && $user->role === 'chef_service') {
            return $query->where('division', $user->division);
        }
        return $query;
    }

    public function index()
    {
        $query = Agent::where('status', 'pending');

        if (Auth::user()->role === 'super_admin') {
            // Un super administrateur voit tous les agents en attente
        } elseif (Auth::user()->role === 'admin_sectoriel') {
            $query = $this->filterAgentsByDirection($query);
        } elseif (Auth::user()->role === 'chef_service') {
            $query = $this->filterAgentsByDivision($query);
        }
        
        $agents = $query->get();
        return view('agent.index', compact('agents'));
    }

    public function validatedIndex()
    {
        $query = Agent::where('status', 'validated');
        $user = Auth::user();

        if (in_array($user->role, ['chef_service', 'directeur', 'admin_sectoriel'])) {
             $query = $this->filterAgentsByDirection($query);
        }
        if ($user->role === 'chef_service') {
            $query = $this->filterAgentsByDivision($query);
        }
        if ($user->role === 'admin_sectoriel') {
            $query->where('role', '!=', 'super_admin');
        }
        if ($user->role === 'directeur') {
            $query->whereNotIn('role', ['super_admin', 'admin_sectoriel']);
        }

        // Ajout du filtre de recherche global
        if (request('search')) {
            $search = trim(request('search'));
            $query->where(function($q) use ($search) {
                $q->where('prenom', 'like', "%$search%")
                  ->orWhere('nom', 'like', "%$search%")
                  ->orWhere('division', 'like', "%$search%")
                  ->orWhere('direction', 'like', "%$search%")
                  ->orWhere('role', 'like', "%$search%")
                ;
            });
        }

        $agents = $query->get();
        return view('agent.validated-agents', compact('agents'));
    }

    public function create()
    {
        return view('auth.register-agent');
    }

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

    public function show(Agent $agent)
    {
        $user = Auth::user();
        if ($user->role === 'agent' && $user->id !== $agent->id) {
             return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent.');
        }
        if (in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur']) && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir les détails de cet agent car il ne fait pas partie de votre direction.');
        }
        return view('agent.details', compact('agent'));
    }

    /**
     * Affiche les détails d'un agent validé.
     * Accessible uniquement si l'agent a le statut 'validated' et selon les droits de l'utilisateur.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function validatedDetails(Agent $agent)
    {
        $user = Auth::user();
        
        // Seuls les agents validés sont accessibles ici
        if ($agent->status !== 'validated') {
            return redirect()->route('agent.validated_index')->with('error', "Cet agent n'est pas validé.");
        }
        // Les admin_sectoriel, chef_service, directeur ne voient que les agents de leur direction
        if (in_array($user->role, ['admin_sectoriel', 'chef_service', 'directeur']) && $agent->direction !== $user->direction) {
            return redirect()->route('agent.validated_index')->with('error', "Vous n'êtes pas autorisé à voir les détails de cet agent.");
        }
        // Les chef_service ne voient que les agents de leur division
        if ($user->role === 'chef_service' && $agent->division !== $user->division) {
            return redirect()->route('agent.validated_index')->with('error', "Vous n'êtes pas autorisé à voir les détails de cet agent.");
        }
        // Les agents ne peuvent voir que leurs propres infos
        if ($user->role === 'agent' && $user->id !== $agent->id) {
            return redirect()->route('agent.validated_index')->with('error', "Vous n'êtes pas autorisé à voir les détails de cet agent.");
        }

        // Récupérer la dernière entrée de validation pour cet agent, avec l'agent validateur
        $validationEntry = ValidationHistorique::where('agent_id', $agent->id)
                                              ->where('action', 'validated') // C'EST LA CLEF : doit correspondre à ce qui est enregistré
                                              ->latest('validated_at')
                                              ->with('validator') // Charger la relation 'validator' pour obtenir les détails de l'agent
                                              ->first();
        
        // Initialiser validatorAgent à null, puis l'assigner si validationEntry existe et a un validateur
        $validatorAgent = null;
        if ($validationEntry && $validationEntry->validator) {
            $validatorAgent = $validationEntry->validator;
        }

        // Passage de l'objet Agent et de l'objet validationEntry (qui contient la relation validator) à la vue
        return view('agent.validated-details', compact('agent', 'validationEntry', 'validatorAgent'));
    }


    public function edit(Agent $agent)
    {
        $user = Auth::user();

        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->route('agent.index')->with('error', 'Vous n\'êtes pas autorisé à éditer cet agent car il ne fait pas partie de votre direction.');
        }
        return view('agent.edit', compact('agent'));
    }

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
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'DCB', 'DODP', 'DS', 'DP', 'DMTA', 'CSS', 'CER'])
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

    /**
     * Valide l'inscription d'un agent, lui attribue un mot de passe et un rôle.
     * Lors de la validation, une entrée est créée dans validation_historiques.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateAndAssignPassword(Request $request, Agent $agent)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['super_admin', 'admin_sectoriel'])) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider des agents ou à leur assigner des rôles.');
        }

        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à valider cet agent car il ne fait pas partie de votre direction.');
        }

        if ($agent->status !== 'pending') {
            if ($agent->status === 'validated') {
                return redirect()->route('agent.validatedDetails', $agent)->with('success', 'Agent déjà validé.'); // Redirige vers la page de détails validés
            }
            return redirect()->back()->with('error', 'Impossible de valider cet agent.');
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

        $updateData = [
            'password' => Hash::make($generatedPassword),
            'status' => 'validated',
            'role' => $request->role_to_assign,
            'must_change_password' => true,
            'validated_at' => now(), // Ajout de la date de validation
        ];

        if (in_array($request->role_to_assign, ['super_admin', 'admin_sectoriel', 'directeur'])) {
            $updateData['division'] = null;
        }

        $agent->update($updateData); // Utilise les données préparées pour la mise à jour

        // Ajout d'une entrée dans ValidationHistorique
        ValidationHistorique::create([
            'agent_id' => $agent->id,
            'user_id' => $user->id,
            'action' => 'validated', // CORRECTION ICI : Doit être 'validated'
            'validated_at' => now(),
            'demande_absence_id' => null, // S'assurer que c'est bien nullable ou gérer la valeur
            'role' => $request->role_to_assign, // Le rôle qui a été attribué
        ]);

        try {
            Mail::to($agent->email)->send(new AgentValidatedMail($agent, $generatedPassword));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'envoi de l'e-mail de validation à {$agent->email}: " . $e->getMessage());
            return redirect()->route('agent.index')->with('warning', 'Agent validé, mais l\'e-mail n\'a pas pu être envoyé.');
        }

        // Message de succès garanti après validation
        // Redirige vers la nouvelle route de détails validés
        return redirect()->route('agent.validatedDetails', $agent->fresh())->with('success', 'Agent validé avec succès et mot de passe et rôle assignés.');
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
    $user->password = Hash::make($request->password);
    $user->must_change_password = false;
    $user->save();

    Auth::logout(); // Déconnexion de l'utilisateur

    // Redirige vers la page de connexion
    return redirect()->route('login')->with('success', 'Connectez-vous avec votre nouveau mot de passe.');
    }

    /**
     * Attribue un rôle à un agent existant.
     * Cette méthode est séparée de la validation initiale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request, Agent $agent)
    {
        $user = Auth::user();

        // Vérification d'autorisation : seuls les super_admin et admin_sectoriel peuvent attribuer un rôle.
        if (!in_array($user->role, ['super_admin', 'admin_sectoriel'])) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à attribuer des rôles.');
        }
        
        $request->validate([
            'role' => ['required', Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])]
        ]);
        $agent->role = $request->role;
        
        // La division devient null si le rôle est 'super_admin', 'admin_sectoriel', ou 'directeur'
        if (in_array($request->role, ['super_admin', 'admin_sectoriel', 'directeur'])) {
            $agent->division = null;
        }

        $agent->save();

        return back()->with('success', 'Rôle attribué avec succès.');
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
                Rule::in(['DAP', 'DCI', 'DSI', 'DPB', 'DCB', 'DODP', 'DS', 'DP', 'DMTA', 'CSS', 'CER'])
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

    /**
     * Affiche les détails d'un agent validé.
     * Accessible uniquement si l'agent a le statut 'validated' et selon les droits de l'utilisateur.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    // Méthode showValidatedAgentDetails supprimée pour éviter la confusion avec validatedDetails
}
