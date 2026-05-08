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
        if ($demande->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$demande->fichier_pdf || !Storage::disk('public')->exists($demande->fichier_pdf)) {
            return back()->with('error', 'Le fichier n\'est pas encore disponible ou a été supprimé.');
        }

        return Storage::disk('public')->download($demande->fichier_pdf);
    }
}
