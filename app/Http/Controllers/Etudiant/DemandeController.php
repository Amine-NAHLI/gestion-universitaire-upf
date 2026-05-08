<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = DemandeAdministrative::where('user_id', auth()->id())->latest()->get();
        return view('etudiant.demandes.index', compact('demandes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:attestation_scolarite,releve_notes,certificat_inscription'
        ]);

        DemandeAdministrative::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Demande soumise avec succès. Vous serez notifié dès qu\'elle sera traitée.');
    }

    public function download(DemandeAdministrative $demande)
    {
        // Vérification de la propriété
        if ($demande->user_id !== auth()->id()) {
            abort(403, "Vous n'êtes pas autorisé à télécharger ce document.");
        }

        // Vérification de l'existence du fichier
        if (!$demande->fichier_pdf || !Storage::disk('public')->exists($demande->fichier_pdf)) {
            abort(404, "Le fichier PDF est introuvable sur le serveur.");
        }

        return Storage::disk('public')->download(
            $demande->fichier_pdf,
            basename($demande->fichier_pdf)
        );
    }
}
