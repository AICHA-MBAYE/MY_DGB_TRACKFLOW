<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // Pour des règles de mot de passe plus robustes

class ForcePasswordChangeController extends Controller
{
    /**
     * Affiche le formulaire de changement de mot de passe forcé.
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        // Assurez-vous que seul l'utilisateur actuellement connecté peut accéder à ce formulaire.
        // Cela est également géré par le middleware, mais une double vérification ne nuit pas.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.force-password-change');
    }

    /**
     * Gère la soumission du formulaire de changement de mot de passe forcé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = Auth::user();

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($request->password);
        // Mettre à jour la colonne 'must_change_password' à false
        $user->must_change_password = false;
        $user->save();

        // Déconnecter l'utilisateur pour qu'il se reconnecte avec son nouveau mot de passe
        // ou le laisser connecté et le rediriger vers le tableau de bord.
        // Pour une sécurité maximale, la déconnexion est souvent préférée.
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été changé avec succès. Veuillez vous connecter avec votre nouveau mot de passe.');
    }
}
