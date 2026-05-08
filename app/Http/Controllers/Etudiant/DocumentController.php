<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $demandes_validees = DemandeAdministrative::where('user_id', auth()->id())
            ->where('statut', 'validee')
            ->whereNotNull('fichier_pdf')
            ->latest()
            ->get();
            
        return view('etudiant.documents.index', compact('demandes_validees'));
    }
}
