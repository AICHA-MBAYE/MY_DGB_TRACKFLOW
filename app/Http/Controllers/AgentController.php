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
        if (Auth::user()->role === 'admin_sectoriel') {
            $query = $this->filterAgentsByDirection($query);
        }
        if (Auth::user()->role === 'chef_service') {
            $query = $this->filterAgentsByDivision($query);
        }
        $agents = $query->get();
        return view('agent.index', compact('agents'));
    }

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
            'division' => 'required|string|max:255', // Division obligatoire
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
        return view('agent.show', compact('agent'));
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
            'division' => 'required|string|max:255', // Division obligatoire aussi à la modification
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

        $agent->update([
            'password' => Hash::make($generatedPassword),
            'status' => 'validated',
            'role' => $request->role_to_assign,
            'must_change_password' => true,
        ]);

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
            'password' => 'required|string|min:8|confirmed',
        ]);

        $agent = Auth::user();
        $agent->password = Hash::make($request->password);
        $agent->must_change_password = false;
        $agent->save();

        return redirect()->route('welcome')->with('success', 'Mot de passe changé avec succès.');
    }

    public function reject(Request $request, Agent $agent)
    {
        $user = Auth::user();
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à rejeter cet agent car il ne fait pas partie de votre direction.');
        }
        if ($agent->status !== 'pending') {
            return redirect()->back()->with('error', 'L\'agent n\'est pas en statut "en attente". Impossible de rejeter.');
        }

        $agent->update([
            'status' => 'rejected',
            'password' => null,
            'role' => $agent->role ?? 'agent',
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

    public function destroy(Agent $agent)
    {
        $user = Auth::user();
        if ($user->role === 'admin_sectoriel' && $agent->direction !== $user->direction) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cet agent car il ne fait pas partie de votre direction.');
        }
        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent supprimé avec succès.');
    }

    public function editRejectedForm(Agent $agent)
    {
        if ($agent->status !== 'rejected') {
            return redirect()->route('welcome')->with('error', 'Accès non autorisé ou demande déjà traitée.');
        }
        return view('auth.edit-rejected-agent', compact('agent'));
    }

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
            'division' => 'required|string|max:255', // Division obligatoire
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

    public function downloadActe($id)
    {
        $demande = DemandeAbsence::findOrFail($id);
        $path = storage_path('app/' . $demande->pdf_path);
        return response()->download($path);
    }

public function profil()
{
    return view('agent.profil'); // Crée la vue resources/views/agent/profil.blade.php
}
}
