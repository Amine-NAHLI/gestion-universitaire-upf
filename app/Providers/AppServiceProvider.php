<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Seance;
use App\Models\Note;
use App\Models\ReservationSalle;
use App\Models\Justificatif;
use App\Models\DemandeAdministrative;
use App\Models\CahierTexte;
use App\Models\Module;
use App\Policies\SeancePolicy;
use App\Policies\NotePolicy;
use App\Policies\ReservationSallePolicy;
use App\Policies\JustificatifPolicy;
use App\Policies\DemandeAdministrativePolicy;
use App\Policies\CahierTextePolicy;
use App\Policies\ModulePolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(Seance::class,              SeancePolicy::class);
        Gate::policy(Note::class,                NotePolicy::class);
        Gate::policy(ReservationSalle::class,    ReservationSallePolicy::class);
        Gate::policy(Justificatif::class,        JustificatifPolicy::class);
        Gate::policy(DemandeAdministrative::class, DemandeAdministrativePolicy::class);
        Gate::policy(CahierTexte::class,         CahierTextePolicy::class);
        Gate::policy(Module::class,              ModulePolicy::class);
    }
}
