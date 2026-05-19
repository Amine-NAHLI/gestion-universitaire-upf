<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParentAuthController extends Controller
{
    /**
     * Show the Parent login form.
     */
    public function showLoginForm(): View
    {
        $etudiants = \App\Models\User::where('role', 'etudiant')->get();
        return view('auth.login-parent', compact('etudiants'));
    }

    /**
     * Handle Parent authentication.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Secretly append the parent suffix to the input email
        $parentEmail = $request->email . '+parent';

        if (Auth::attempt([
            'email' => $parentEmail,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => __('Ce compte parent est en attente d\'activation car le compte étudiant associé n\'est pas encore validé.'),
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->route('parent.dashboard');
        }

        return back()->withErrors([
            'email' => __('Adresse e-mail ou mot de passe incorrect pour l\'Espace Parents.'),
        ])->onlyInput('email');
    }
}
