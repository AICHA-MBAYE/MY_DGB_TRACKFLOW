<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->must_change_password && !$request->is('password/change') && !$request->is('logout')) {
            return redirect()->route('password.change.form');
        }
        return $next($request);
    }
}