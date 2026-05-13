<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Used by statistics distribution and averages
            $table->index('note_finale');
            // Used heavily in professeur dashboard progress
            $table->index(['module_id', 'annee_universitaire']);
        });

        Schema::table('absences', function (Blueprint $table) {
            $table->index('created_at');
            // Common filters / joins
            if (Schema::hasColumn('absences', 'etudiant_id')) {
                $table->index('etudiant_id');
            }
            if (Schema::hasColumn('absences', 'seance_id')) {
                $table->index('seance_id');
            }
        });

        Schema::table('demandes_administratives', function (Blueprint $table) {
            $table->index('statut');
            $table->index('type');
        });

        Schema::table('notifications_app', function (Blueprint $table) {
            $table->index('lue');
            $table->index('created_at');
        });
        
        Schema::table('supports_cours', function (Blueprint $table) {
            $table->index('module_id');
        });

        // Some tables may exist without dedicated models here; indexes stay harmless.
        if (Schema::hasTable('seances')) {
            Schema::table('seances', function (Blueprint $table) {
                if (Schema::hasColumn('seances', 'professeur_id')) {
                    $table->index('professeur_id');
                }
                if (Schema::hasColumn('seances', 'date')) {
                    $table->index('date');
                }
                if (Schema::hasColumn('seances', 'statut')) {
                    $table->index('statut');
                }
                if (Schema::hasColumn('seances', 'professeur_id') && Schema::hasColumn('seances', 'date')) {
                    $table->index(['professeur_id', 'date']);
                }
            });
        }

        if (Schema::hasTable('module_professeur')) {
            Schema::table('module_professeur', function (Blueprint $table) {
                if (Schema::hasColumn('module_professeur', 'professeur_id')) {
                    $table->index('professeur_id');
                }
                if (Schema::hasColumn('module_professeur', 'module_id')) {
                    $table->index('module_id');
                }
                if (Schema::hasColumn('module_professeur', 'professeur_id') && Schema::hasColumn('module_professeur', 'module_id')) {
                    $table->index(['professeur_id', 'module_id']);
                }
            });
        }

        if (Schema::hasTable('module_groupe')) {
            Schema::table('module_groupe', function (Blueprint $table) {
                if (Schema::hasColumn('module_groupe', 'module_id')) {
                    $table->index('module_id');
                }
                if (Schema::hasColumn('module_groupe', 'groupe_id')) {
                    $table->index('groupe_id');
                }
                if (Schema::hasColumn('module_groupe', 'module_id') && Schema::hasColumn('module_groupe', 'groupe_id')) {
                    $table->index(['module_id', 'groupe_id']);
                }
            });
        }

        if (Schema::hasTable('etudiants') && Schema::hasColumn('etudiants', 'groupe_id')) {
            Schema::table('etudiants', function (Blueprint $table) {
                $table->index('groupe_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['note_finale']);
            $table->dropIndex(['module_id', 'annee_universitaire']);
        });

        Schema::table('absences', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            if (Schema::hasColumn('absences', 'etudiant_id')) {
                $table->dropIndex(['etudiant_id']);
            }
            if (Schema::hasColumn('absences', 'seance_id')) {
                $table->dropIndex(['seance_id']);
            }
        });

        Schema::table('demandes_administratives', function (Blueprint $table) {
            $table->dropIndex(['statut']);
            $table->dropIndex(['type']);
        });

        Schema::table('notifications_app', function (Blueprint $table) {
            $table->dropIndex(['lue']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('supports_cours', function (Blueprint $table) {
            $table->dropIndex(['module_id']);
        });

        if (Schema::hasTable('seances')) {
            Schema::table('seances', function (Blueprint $table) {
                if (Schema::hasColumn('seances', 'professeur_id')) {
                    $table->dropIndex(['professeur_id']);
                }
                if (Schema::hasColumn('seances', 'date')) {
                    $table->dropIndex(['date']);
                }
                if (Schema::hasColumn('seances', 'statut')) {
                    $table->dropIndex(['statut']);
                }
                if (Schema::hasColumn('seances', 'professeur_id') && Schema::hasColumn('seances', 'date')) {
                    $table->dropIndex(['professeur_id', 'date']);
                }
            });
        }

        if (Schema::hasTable('module_professeur')) {
            Schema::table('module_professeur', function (Blueprint $table) {
                if (Schema::hasColumn('module_professeur', 'professeur_id')) {
                    $table->dropIndex(['professeur_id']);
                }
                if (Schema::hasColumn('module_professeur', 'module_id')) {
                    $table->dropIndex(['module_id']);
                }
                if (Schema::hasColumn('module_professeur', 'professeur_id') && Schema::hasColumn('module_professeur', 'module_id')) {
                    $table->dropIndex(['professeur_id', 'module_id']);
                }
            });
        }

        if (Schema::hasTable('module_groupe')) {
            Schema::table('module_groupe', function (Blueprint $table) {
                if (Schema::hasColumn('module_groupe', 'module_id')) {
                    $table->dropIndex(['module_id']);
                }
                if (Schema::hasColumn('module_groupe', 'groupe_id')) {
                    $table->dropIndex(['groupe_id']);
                }
                if (Schema::hasColumn('module_groupe', 'module_id') && Schema::hasColumn('module_groupe', 'groupe_id')) {
                    $table->dropIndex(['module_id', 'groupe_id']);
                }
            });
        }

        if (Schema::hasTable('etudiants') && Schema::hasColumn('etudiants', 'groupe_id')) {
            Schema::table('etudiants', function (Blueprint $table) {
                $table->dropIndex(['groupe_id']);
            });
        }
    }
};
