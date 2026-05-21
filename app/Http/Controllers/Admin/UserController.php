<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Module;
use App\Models\Niveau;
use App\Models\Professeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EtudiantsExport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('status') && $request->status === 'pending') {
            $query->where('is_active', false);
        } elseif ($request->has('role') && in_array($request->role, ['admin', 'professeur', 'etudiant'])) {
            $query->where('role', $request->role);
        }

        $users = $query->with(['etudiant', 'professeur'])->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'       => User::count(),
            'admins'      => User::where('role', 'admin')->count(),
            'professeurs' => User::where('role', 'professeur')->count(),
            'etudiants'   => User::where('role', 'etudiant')->count(),
            'pending'     => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $filieres          = Filiere::orderBy('nom')->get();
        $modulesParFiliere = $this->buildModulesParFiliere();
        $niveaux           = collect();
        $groupes           = collect();

        if (old('filiere_id')) {
            $niveaux = Niveau::where('filiere_id', old('filiere_id'))
                ->orderBy('numero')->get(['id', 'nom', 'numero']);
        }
        if (old('niveau_id')) {
            $groupes = Groupe::where('niveau_id', old('niveau_id'))
                ->orderBy('nom')->get(['id', 'nom']);
        }

        $modulesSelectionnes = array_map('strval', (array) old('modules', []));

        return view('admin.users.create', compact(
            'filieres', 'modulesParFiliere', 'niveaux', 'groupes', 'modulesSelectionnes'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'prenom'    => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', 'in:admin,professeur,etudiant'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ];

        if ($request->role === 'etudiant') {
            $rules['filiere_id'] = ['required', 'exists:filieres,id'];
            $rules['niveau_id']  = ['required', 'exists:niveaux,id'];
            $rules['groupe_id']  = ['required', 'exists:groupes,id'];
        }

        if ($request->role === 'professeur') {
            $rules['modules']    = ['required', 'array', 'min:1'];
            $rules['modules.*']  = ['exists:modules,id'];
            $rules['specialite'] = ['nullable', 'string', 'max:255'];
            $rules['grade']      = ['required', 'in:vacataire,assistant,maitre_assistant,professeur'];
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'              => $request->name,
                'prenom'            => $request->prenom,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'role'              => $request->role,
                'telephone'         => $request->telephone,
                'is_active'         => $request->boolean('is_active'),
                'email_verified_at' => now(),
            ]);

            if ($user->isEtudiant()) {
                $nextId = (Etudiant::max('id') ?? 0) + 1;

                Etudiant::create([
                    'user_id'          => $user->id,
                    'cne'              => 'CNE' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'matricule'        => 'E' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'groupe_id'        => $request->groupe_id,
                    'date_inscription' => now(),
                    'statut'           => 'inscrit',
                ]);

                User::create([
                    'name'              => $user->name,
                    'prenom'            => 'Parent de ' . $user->prenom,
                    'email'             => $user->email . '+parent',
                    'password'          => Hash::make($request->password),
                    'role'              => 'parent',
                    'is_active'         => $user->is_active,
                    'email_verified_at' => now(),
                ]);
            }

            if ($user->isProfesseur()) {
                $nextId = (Professeur::max('id') ?? 0) + 1;

                $professeur = Professeur::create([
                    'user_id'          => $user->id,
                    'matricule'        => 'P' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'specialite'       => $request->specialite,
                    'grade'            => $request->grade,
                    'date_recrutement' => now(),
                ]);

                $professeur->modules()->attach($request->modules);
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['etudiant.groupe.niveau.filiere', 'professeur.modules.niveau.filiere']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $filieres            = Filiere::orderBy('nom')->get();
        $modulesParFiliere   = $this->buildModulesParFiliere();
        $filiereId           = '';
        $niveauId            = '';
        $groupeId            = '';
        $niveaux             = collect();
        $groupes             = collect();
        $modulesSelectionnes = [];

        if ($user->isEtudiant() && $user->etudiant) {
            $etudiant  = $user->etudiant->load('groupe.niveau.filiere');
            $groupeId  = (string) ($etudiant->groupe_id ?? '');
            $niveauId  = (string) ($etudiant->groupe->niveau_id ?? '');
            $filiereId = (string) ($etudiant->groupe->niveau->filiere_id ?? '');

            if ($filiereId) {
                $niveaux = Niveau::where('filiere_id', $filiereId)
                    ->orderBy('numero')->get(['id', 'nom', 'numero']);
            }
            if ($niveauId) {
                $groupes = Groupe::where('niveau_id', $niveauId)
                    ->orderBy('nom')->get(['id', 'nom']);
            }
        }

        if ($user->isProfesseur() && $user->professeur) {
            $modulesSelectionnes = array_map(
                'strval',
                $user->professeur->modules->pluck('id')->toArray()
            );
        }

        return view('admin.users.edit', compact(
            'user', 'filieres', 'modulesParFiliere',
            'filiereId', 'niveauId', 'groupeId',
            'niveaux', 'groupes', 'modulesSelectionnes'
        ));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'prenom'    => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'      => ['required', 'in:admin,professeur,etudiant'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ];

        if ($request->role === 'etudiant') {
            $rules['groupe_id'] = ['required', 'exists:groupes,id'];
        }

        if ($request->role === 'professeur') {
            $rules['modules']    = ['required', 'array', 'min:1'];
            $rules['modules.*']  = ['exists:modules,id'];
            $rules['specialite'] = ['nullable', 'string', 'max:255'];
            $rules['grade']      = ['required', 'in:vacataire,assistant,maitre_assistant,professeur'];
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $user) {
            $oldEmail = $user->email;

            $data = [
                'name'      => $request->name,
                'prenom'    => $request->prenom,
                'email'     => $request->email,
                'role'      => $request->role,
                'telephone' => $request->telephone,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($user->isEtudiant()) {
                if ($user->etudiant) {
                    $user->etudiant->update(['groupe_id' => $request->groupe_id]);
                } else {
                    $nextId = (Etudiant::max('id') ?? 0) + 1;
                    Etudiant::create([
                        'user_id'          => $user->id,
                        'cne'              => 'CNE' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                        'matricule'        => 'E' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                        'groupe_id'        => $request->groupe_id,
                        'date_inscription' => now(),
                        'statut'           => 'inscrit',
                    ]);
                }

                $parent = User::where('email', $oldEmail . '+parent')->first();
                if ($parent) {
                    $parentData = [
                        'name'      => $user->name,
                        'email'     => $user->email . '+parent',
                        'is_active' => $user->is_active,
                    ];
                    if ($request->filled('password')) {
                        $parentData['password'] = Hash::make($request->password);
                    }
                    $parent->update($parentData);
                }
            }

            if ($user->isProfesseur()) {
                if ($user->professeur) {
                    $user->professeur->update([
                        'specialite' => $request->specialite,
                        'grade'      => $request->grade,
                    ]);
                    $user->professeur->modules()->sync($request->modules ?? []);
                } else {
                    $nextId = (Professeur::max('id') ?? 0) + 1;
                    $professeur = Professeur::create([
                        'user_id'          => $user->id,
                        'matricule'        => 'P' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                        'specialite'       => $request->specialite,
                        'grade'            => $request->grade,
                        'date_recrutement' => now(),
                    ]);
                    $professeur->modules()->attach($request->modules ?? []);
                }
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if (!$user->is_active) {
            try {
                Mail::send('emails.account-rejected', [
                    'name' => $user->full_name
                ], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Mise à jour concernant votre demande d\'inscription UPF');
                });
            } catch (\Exception $e) {
                Log::error("Erreur d'envoi d'email de refus : " . $e->getMessage());
            }
        }

        DB::transaction(function () use ($user) {
            \App\Models\DemandeAdministrative::where('user_id', $user->id)->delete();
            \App\Models\NotificationApp::where('user_id', $user->id)->delete();

            if ($user->isEtudiant()) {
                if ($user->etudiant) {
                    \App\Models\Note::where('etudiant_id', $user->etudiant->id)->delete();
                    \App\Models\Absence::where('etudiant_id', $user->etudiant->id)->delete();
                    $user->etudiant()->delete();
                }

                $parent = User::where('email', $user->email . '+parent')->first();
                if ($parent) {
                    $parent->delete();
                }
            }

            if ($user->isProfesseur() && $user->professeur) {
                $user->professeur->modules()->detach();
                $user->professeur()->delete();
            }

            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        if ($user->isEtudiant()) {
            $parent = User::where('email', $user->email . '+parent')->first();
            if ($parent) {
                $parent->is_active = $user->is_active;
                $parent->save();
            }
        }

        if ($user->is_active) {
            // Fallback: créer le profil si absent (cas des auto-inscriptions sans groupe défini)
            if ($user->isEtudiant() && !$user->etudiant) {
                $groupe = Groupe::first();
                $nextId = (Etudiant::max('id') ?? 0) + 1;

                Etudiant::create([
                    'user_id'          => $user->id,
                    'cne'              => 'CNE' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'matricule'        => 'E' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'groupe_id'        => $groupe?->id,
                    'date_inscription' => now(),
                    'statut'           => 'inscrit',
                ]);
            } elseif ($user->isProfesseur() && !$user->professeur) {
                $nextId = (Professeur::max('id') ?? 0) + 1;

                Professeur::create([
                    'user_id'          => $user->id,
                    'matricule'        => 'P' . str_pad($nextId, 6, '0', STR_PAD_LEFT),
                    'specialite'       => null,
                    'grade'            => 'assistant',
                    'date_recrutement' => now(),
                ]);
            }

            try {
                Mail::send('emails.account-approved', [
                    'name'     => $user->full_name,
                    'email'    => $user->email,
                    'loginUrl' => route('login'),
                ], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Félicitations ! Votre compte UPF a été approuvé');
                });
            } catch (\Exception $e) {
                Log::error("Erreur d'envoi d'email de validation : " . $e->getMessage());
            }
        }

        $status = $user->is_active ? 'activé' : 'désactivé';
        $type   = $user->is_active ? 'success' : 'warning';

        return redirect()->back()
            ->with($type, "Le compte de {$user->full_name} a été {$status}.");
    }

    private function buildModulesParFiliere(): array
    {
        return Module::with('niveau.filiere')
            ->orderBy('nom')
            ->get()
            ->groupBy(fn($m) => optional(optional($m->niveau)->filiere)->nom ?? 'Autres')
            ->map(fn($mods, $filiere) => [
                'filiere' => $filiere,
                'modules' => $mods->map(fn($m) => [
                    'id'   => $m->id,
                    'nom'  => $m->nom,
                    'code' => $m->code,
                ])->values()->toArray(),
            ])
            ->values()
            ->toArray();
    }

    public function export(Request $request)
    {
        $groupe_id  = $request->query('groupe_id');
        $filiere_id = $request->query('filiere_id');
        return Excel::download(new EtudiantsExport($groupe_id, $filiere_id), 'etudiants.xlsx');
    }
}
