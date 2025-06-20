<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // Redirection par défaut vers le tableau de bord
    protected $redirectTo = '/'; 

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // NOUVEAU : Vérifier si l'utilisateur doit changer son mot de passe
        // Si 'must_change_password' est vrai, rediriger vers la page de changement de mot de passe forcé
        if ($user && $user->must_change_password) {
            return redirect()->route('password.force_change');
        }

        // Si aucun changement forcé n'est requis, rediriger vers la destination prévue (tableau de bord par défaut)
        return redirect()->intended($this->redirectTo);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
