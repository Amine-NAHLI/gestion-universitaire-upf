<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\NotificationApp;
use App\Mail\NouvelleDemandeAdmin;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = DemandeAdministrative::where('user_id', auth()->id())->latest()->get();
        return view('professeur.demandes.index', compact('demandes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:attestation_travail,ordre_mission',
            'donnees_supplementaires' => 'nullable|array',
        ]);

        $demande = DemandeAdministrative::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'donnees_supplementaires' => $request->donnees_supplementaires,
            'statut' => 'en_attente',
        ]);

        // Notifier tous les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationApp::create([
                'user_id' => $admin->id,
                'type' => 'INFO',
                'titre' => 'Nouvelle demande (Prof) : ' . auth()->user()->full_name,
                'message' => 'Un professeur a soumis une demande de ' . str_replace('_', ' ', $demande->type),
                'lien' => route('admin.demandes.index'),
            ]);
            
            Mail::to($admin->email)->send(new NouvelleDemandeAdmin($demande));
        }

        return back()->with('success', 'Demande soumise avec succès. Les administrateurs ont été notifiés.');
    }

    public function download(DemandeAdministrative $demande)
    {
        if ($demande->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$demande->fichier_pdf || !Storage::disk('public')->exists($demande->fichier_pdf)) {
            abort(404, "Le fichier PDF est introuvable sur le serveur.");
        }

        return Storage::disk('public')->download(
            $demande->fichier_pdf,
            basename($demande->fichier_pdf)
        );
    }
}
