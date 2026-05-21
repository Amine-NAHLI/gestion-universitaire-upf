<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Justificatif;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\NotificationApp;
use App\Mail\NouveauJustificatifAdmin;

class JustificatifController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $absences_non_justifiees = Absence::with('seance.module')
            ->where('etudiant_id', $etudiant->id)
            ->where('justifiee', false)
            ->whereDoesntHave('justificatif')
            ->get();

        $justificatifs = Justificatif::with('absence.seance.module')
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();

        return view('etudiant.justificatifs.index', compact('absences_non_justifiees', 'justificatifs'));
    }

    public function store(Request $request)
    {
        $etudiant = auth()->user()->etudiant;

        $request->validate([
            'absence_id' => 'required|exists:absences,id',
            'motif' => 'required|string|min:10',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Vérification que l'absence appartient bien à cet étudiant (protection IDOR)
        $absence = Absence::where('id', $request->absence_id)
            ->where('etudiant_id', $etudiant->id)
            ->firstOrFail();

        $path = $request->file('fichier')->store('justificatifs', 'public');

        $justificatif = Justificatif::create([
            'etudiant_id' => $etudiant->id,
            'absence_id' => $absence->id,
            'fichier' => $path,
            'motif' => $request->motif,
            'statut' => 'en_attente',
        ]);

        // Notifier tous les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationApp::create([
                'user_id' => $admin->id,
                'type' => 'INFO',
                'titre' => 'Nouveau justificatif : ' . auth()->user()->full_name,
                'message' => 'Un étudiant a déposé un justificatif pour son absence.',
                'lien' => route('admin.absences.index'),
            ]);

            try {
                Mail::to($admin->email)->send(new NouveauJustificatifAdmin($justificatif));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Erreur email justificatif admin {$admin->id}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Justificatif déposé avec succès. Les administrateurs ont été notifiés.');
    }
}
