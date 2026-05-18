<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérification si l'utilisateur est actif
        if (!$user->is_active) {
            Auth::logout();
            if ($user->email_verified_at === null) {
                return redirect()->route('login')->with('error', 'Veuillez d\'abord valider votre adresse email en cliquant sur le lien reçu.');
            }
            return redirect()->route('login')->with('error', 'Votre inscription a été validée par email. Elle est maintenant en cours d\'approbation par l\'administration.');
        }

        // Vérification du rôle
        if (!in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
