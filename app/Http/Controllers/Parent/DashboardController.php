<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private function getStudentData(): array
    {
        $parent = Auth::user();

        $studentEmail = str_replace('+parent', '', $parent->email);
        $studentUser  = User::where('email', $studentEmail)->first();
        $student      = $studentUser?->etudiant;

        $dotColors = ['#818cf8', '#a78bfa', '#f472b6', '#34d399', '#fb923c', '#60a5fa', '#e879f9'];

        $blank = [
            'student'               => null,
            'studentName'           => null,
            'moyenne'               => null,
            'mention'               => null,
            'mentionCouleur'        => null,
            'absencesTotal'         => 0,
            'absencesNonJustifiees' => 0,
            'absencesAlert'         => false,
            'demandesEnAttente'     => 0,
            'prochaineSeance'       => null,
            'notesLabels'           => [],
            'notesData'             => [],
            'notesMoyenne'          => null,
            'emploiDuTemps'         => array_fill_keys(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'], []),
            'jours'                 => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
            'edtTotal'              => 0,
            'dotColors'             => $dotColors,
            'jourActuel'            => null,
            'lundiFmt'              => null,
            'vendrediFmt'           => null,
            'suggestionsChat'       => [],
        ];

        if (! $student) {
            return $blank;
        }

        $student->load([
            'notes.module',
            'absences',
            'groupe.niveau.filiere',
            'user.demandesAdministratives',
        ]);

        // ── Moyenne & mention ──────────────────────────────────────────────
        $notesAvecValeur = $student->notes->whereNotNull('note_finale');
        $moyenne         = $notesAvecValeur->isNotEmpty()
            ? round($notesAvecValeur->avg('note_finale'), 2)
            : null;

        if ($moyenne === null) {
            $mention = null;
            $mentionCouleur = null;
        } elseif ($moyenne >= 16) {
            $mention = 'Très Bien';
            $mentionCouleur = 'green';
        } elseif ($moyenne >= 14) {
            $mention = 'Bien';
            $mentionCouleur = 'green';
        } elseif ($moyenne >= 12) {
            $mention = 'Assez Bien';
            $mentionCouleur = 'yellow';
        } elseif ($moyenne >= 10) {
            $mention = 'Passable';
            $mentionCouleur = 'yellow';
        } else {
            $mention = 'Insuffisant';
            $mentionCouleur = 'red';
        }

        // ── Absences du mois en cours ──────────────────────────────────────
        $absencesMois          = $student->absences->filter(
            fn ($a) => $a->date_creation?->isCurrentMonth()
        );
        $absencesTotal         = $absencesMois->count();
        $absencesNonJustifiees = $absencesMois->where('justifiee', false)->count();
        $absencesAlert         = $absencesNonJustifiees >= 3;

        // ── Demandes en attente ────────────────────────────────────────────
        $demandesEnAttente = $student->user->demandesAdministratives
            ->where('statut', 'en_attente')
            ->count();

        // ── Prochaine séance ──────────────────────────────────────────────
        $prochaineSeance = Seance::where('groupe_id', $student->groupe_id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->with(['module', 'salle'])
            ->first();

        // ── Emploi du temps de la semaine ────────────────────────────────
        $dayMap = [
            'Monday'    => 'Lundi',
            'Tuesday'   => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday'  => 'Jeudi',
            'Friday'    => 'Vendredi',
        ];
        $semaineLundi    = now()->startOfWeek();
        $semaineVendredi = now()->startOfWeek()->addDays(4);

        $seancesSemaine = Seance::where('groupe_id', $student->groupe_id)
            ->whereBetween('date', [$semaineLundi, now()->endOfWeek()])
            ->with(['module', 'salle', 'professeur.user'])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();

        $emploiDuTemps = array_fill_keys(array_values($dayMap), []);
        foreach ($seancesSemaine as $seance) {
            $frDay = $dayMap[$seance->date->englishDayOfWeek] ?? null;
            if ($frDay) {
                $emploiDuTemps[$frDay][] = $seance;
            }
        }

        $jourActuel  = $dayMap[now()->englishDayOfWeek] ?? null;
        $lundiFmt    = $semaineLundi->format('d/m');
        $vendrediFmt = $semaineVendredi->format('d/m');

        // ── Données graphique ─────────────────────────────────────────────
        $notesLabels = [];
        $notesData   = [];
        foreach ($notesAvecValeur as $note) {
            $notesLabels[] = $note->module->nom;
            $notesData[]   = round((float) $note->note_finale, 2);
        }

        // ── Suggestions contextuelles chatbot ─────────────────────────────
        $suggestions = ['Quelles sont ses dernières notes ?'];
        if ($absencesNonJustifiees > 0) {
            $suggestions[] = "Il a {$absencesNonJustifiees} absence(s) non justifiée(s), que faire ?";
        } else {
            $suggestions[] = 'A-t-il des absences ce mois-ci ?';
        }
        if ($demandesEnAttente > 0) {
            $suggestions[] = "Où en est sa demande administrative ?";
        } else {
            $suggestions[] = "Comment soumettre une nouvelle demande ?";
        }
        if ($moyenne !== null && $moyenne < 10) {
            $suggestions[] = "Sa moyenne est insuffisante, quels sont les risques ?";
        }
        if ($prochaineSeance !== null) {
            $suggestions[] = "Quand est son prochain cours ?";
        } else {
            $suggestions[] = "Quel est son emploi du temps ?";
        }
        $suggestionsChat = array_slice($suggestions, 0, 3);

        return [
            'student'               => $student,
            'studentName'           => $student->user->prenom,
            'moyenne'               => $moyenne,
            'mention'               => $mention,
            'mentionCouleur'        => $mentionCouleur,
            'absencesTotal'         => $absencesTotal,
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'absencesAlert'         => $absencesAlert,
            'demandesEnAttente'     => $demandesEnAttente,
            'prochaineSeance'       => $prochaineSeance,
            'notesLabels'           => $notesLabels,
            'notesData'             => $notesData,
            'notesMoyenne'          => $moyenne,
            'emploiDuTemps'         => $emploiDuTemps,
            'jours'                 => array_values($dayMap),
            'edtTotal'              => $seancesSemaine->count(),
            'dotColors'             => $dotColors,
            'jourActuel'            => $jourActuel,
            'lundiFmt'              => $lundiFmt,
            'vendrediFmt'           => $vendrediFmt,
            'suggestionsChat'       => $suggestionsChat,
        ];
    }

    public function index(): View
    {
        return view('parent.dashboard', $this->getStudentData());
    }

    public function notes(): View
    {
        return view('parent.notes', $this->getStudentData());
    }

    public function edt(): View
    {
        return view('parent.edt', $this->getStudentData());
    }

    public function absences(): View
    {
        return view('parent.absences', $this->getStudentData());
    }
}
