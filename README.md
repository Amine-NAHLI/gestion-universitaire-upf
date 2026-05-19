# UPF Gestion Universitaire

> Plateforme de gestion universitaire complète pour l'**Université Privée de Fès (UPF)**.
> Développée avec Laravel 12, Alpine.js, Tailwind CSS et un chatbot IA propulsé par Groq/Llama.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=alpinedotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/Licence-MIT-green)

---

## Présentation

UPF Gestion Universitaire est une application web multi-rôles permettant la gestion complète d'un établissement d'enseignement supérieur :

- **Administration** : gestion des utilisateurs, filières, modules, emploi du temps, statistiques
- **Professeurs** : saisie des notes, appel, cahier de textes, classroom, réservations de salles
- **Étudiants** : consultation des notes/absences, justificatifs, demandes administratives avec PDF signé
- **Parents** : tableau de bord de suivi, chatbot IA contextuel, alertes nocturnes automatisées

---

## Fonctionnalités par rôle

### Administrateur
- Gestion des utilisateurs (CRUD complet avec activation/désactivation)
- Création automatique du compte parent lors de la création d'un étudiant
- Approbation des inscriptions en attente par email
- Gestion des filières, niveaux et groupes (3 filières : GINFO, GC, GIND)
- Gestion des modules par filière/niveau
- Gestion des salles de cours
- Emploi du temps interactif (FullCalendar — cours/TD/TP/examen)
- Validation/refus des demandes administratives avec génération PDF signé cryptographiquement
- Validation/refus des justificatifs d'absence
- Statistiques globales (distribution des notes, absences par mois, moyennes par module, répartition par filière)
- Export Excel des absences par groupe
- Tableau de bord avec indicateurs temps réel

### Professeur
- Tableau de bord personnalisé (séances du jour, statistiques)
- Feuille de présence (appel par séance avec marquage présent/absent)
- Saisie des notes (CC1, CC2, Examen) — calcul automatique note finale = (CC1+CC2)/2 × 0.4 + Examen × 0.6
- Cahier de textes (objectif et contenu saisi par séance)
- Emploi du temps (vue hebdomadaire)
- Réservations de salles
- Classroom : publication d'annonces épinglables et upload de supports de cours (20 MB max)
- Demandes administratives (attestation de travail, ordre de mission)

### Étudiant
- Tableau de bord avec planning du jour
- Consultation des notes avec moyenne générale et progression par module
- Historique des absences avec statut de justification
- Soumission de justificatifs (PDF, JPG, PNG — 5 MB max)
- Demandes administratives (attestation de scolarité, relevé de notes, certificat d'inscription)
- Téléchargement de PDF signés avec QR code de vérification
- Relevé de notes officiel cryptographiquement signé (RSA 2048 bits)
- Classroom : consultation des annonces, téléchargement des supports de cours, commentaires
- Notifications en temps réel (notes mises à jour, emploi du temps modifié...)

### Parent
- Tableau de bord de suivi scolaire complet
- Moyenne générale avec mention colorée (Très Bien vert, Bien vert, Assez Bien jaune, Passable jaune, Insuffisant rouge)
- Statistiques des absences du mois (justifiées / non justifiées) avec alerte si >= 3 non justifiées
- Prochaine séance de l'étudiant
- Emploi du temps de la semaine courante (grille 5 jours)
- Graphique des notes par module (Chart.js, horizontal bar)
- Notifications et alertes IA (info, warning, urgent) avec marquage lu
- **Chatbot IA contextuel** (Groq Llama 3.3-70b) — 20 questions/jour
  - Message de bienvenue personnalisé généré par IA
  - Historique persistant des conversations
  - Suggestions contextuelles adaptées au profil de l'étudiant
  - Feedback (pouces haut/bas) sur chaque réponse
- **Alertes nocturnes automatisées** : analyse IA quotidienne et notification push si alerte détectée

---

## Stack Technique

### Backend (composer.json)
| Technologie | Version | Usage |
|---|---|---|
| PHP | ^8.2 | Langage principal |
| Laravel | ^12.0 | Framework MVC |
| Laravel Sanctum | ^4.0 | Authentification API tokens |
| Barryvdh DomPDF | ^3.1 | Génération de PDFs |
| Intervention Image | ^3.11 | Traitement des images |
| Maatwebsite Excel | ^3.1 | Export Excel (.xlsx) |
| Simple QR Code | ^4.2 | Génération de QR codes SVG |
| Spatie Permissions | ^6.25 | Gestion des rôles/permissions |
| Laravel Breeze | ^2.4 | Scaffolding auth (dev) |
| DebugBar | ^4.2 | Débogage (dev uniquement) |

### Frontend (package.json)
| Technologie | Version | Usage |
|---|---|---|
| Tailwind CSS | ^3.1.0 | Framework CSS utilitaire |
| Alpine.js | ^3.15.12 | Réactivité JavaScript légère |
| Chart.js | ^4.5.1 | Graphiques et visualisations |
| FullCalendar Core | ^6.1.20 | Emploi du temps interactif |
| FullCalendar DayGrid | ^6.1.20 | Vue mensuelle |
| FullCalendar TimeGrid | ^6.1.20 | Vue hebdomadaire |
| SweetAlert2 | ^11.26.24 | Modales de confirmation |
| Flatpickr | ^4.6.13 | Sélecteurs de dates |
| Notyf | ^3.10.0 | Notifications toast |
| AOS | ^2.3.4 | Animations au défilement |
| GSAP | ^3.15.0 | Animations avancées |
| Axios | ^1.16.0 | Requêtes HTTP |
| Vite | ^7.0.7 | Bundler et hot reload |
| @tailwindcss/forms | ^0.5.2 | Styles formulaires |

### IA et Services externes
| Service | Usage |
|---|---|
| Groq API (Llama 3.3-70b) | Chatbot parent + alertes nocturnes |
| OpenSSL (PHP natif) | Signature RSA 2048 bits des documents |

---

## Prérequis

- PHP 8.2 ou supérieur
- Composer 2.x
- Node.js 18+ et npm
- MySQL 8.0+ (ou SQLite pour le développement)
- Extensions PHP : `openssl`, `gd` ou `imagick`, `pdo_mysql`, `zip`, `mbstring`

---

## Installation

### 1. Cloner et installer les dépendances

```bash
git clone <url-du-repo> gestion-universitaire
cd gestion-universitaire
composer install
npm install
```

### 2. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` avec les valeurs réelles :
```dotenv
APP_NAME="UPF Gestion Universitaire"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_universitaire
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
# En production, utiliser smtp, mailtrap, mailgun...
MAIL_FROM_ADDRESS=noreply@upf.ma
MAIL_FROM_NAME="UPF Gestion"

# Chatbot IA (obtenir sur console.groq.com)
GROQ_API_KEY=gsk_xxxxxxxxxxxx
GROQ_MODEL=llama-3.3-70b-versatile

# URL du frontend React de vérification QR
APP_VERIFICATION_FRONTEND_URL=http://localhost:5173
```

### 3. Migrer et seeder la base de données

```bash
php artisan migrate --seed
```

Cela crée :
- Le compte admin : `nahliamine95@gmail.com` / `password`
- Les 3 filières (GINFO, GC, GIND)
- Les niveaux (1A, 2A, 3A par filière)
- Les groupes (GINFO3A, GINFO3B, GC3A, GC3B, GIND3A, GIND3B)
- Les modules (8 modules GINFO3, modules GC3 et GIND3)
- Des salles de cours

### 4. Générer les clés RSA pour la signature cryptographique

```bash
php artisan tinker
>>> app(App\Services\CryptoSignatureService::class)->generateKeyPair();
>>> exit
```

Les clés sont générées dans `storage/app/keys/` :
- `upf_private.pem` (permissions 600)
- `upf_public.pem`

### 5. Lier le stockage public

```bash
php artisan storage:link
```

### 6. Compiler les assets frontend

```bash
npm run build
# ou en développement avec hot reload :
npm run dev
```

### 7. Lancer le serveur de développement

```bash
# Tout en un (serveur + queue + logs + vite) :
composer dev

# Ou séparément :
php artisan serve
php artisan queue:listen --tries=1
```

---

## Comptes de démonstration

Après `php artisan db:seed` :

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | `nahliamine95@gmail.com` | `password` |

> Les professeurs et étudiants sont créés manuellement par l'administrateur via le panneau admin, ou via la page d'inscription publique (`/register`).

### Flux de création d'un étudiant
1. L'admin crée un étudiant avec l'email `jean.dupont@etudiant.upf.ma`
2. Un compte parent est automatiquement créé : email `jean.dupont@etudiant.upf.ma+parent`, même mot de passe
3. Le parent se connecte sur `/login/parent`

### Page d'inscription publique
Les étudiants peuvent s'auto-inscrire via `/register`. Leur compte est **inactif** jusqu'à approbation par l'administrateur via le panneau "Utilisateurs en attente".

---

## Structure du projet

```
gestion-universitaire/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # DashboardController, UserController, FiliereController,
│   │   │   │                    # ModuleController, SalleController, AbsenceController,
│   │   │   │                    # NoteController, EdtController, DemandeController,
│   │   │   │                    # StatistiqueController
│   │   │   ├── Professeur/      # DashboardController, ModuleController, NoteController,
│   │   │   │                    # AbsenceController, CahierController, EdtController,
│   │   │   │                    # ReservationController, ClassroomController, DemandeController
│   │   │   ├── Etudiant/        # DashboardController, NoteController, AbsenceController,
│   │   │   │                    # JustificatifController, DemandeController,
│   │   │   │                    # ClassroomController, DocumentController,
│   │   │   │                    # ReleveNotesController, EdtController
│   │   │   ├── Parent/          # DashboardController, ChatbotController,
│   │   │   │                    # NotificationController
│   │   │   ├── Api/             # AuthController, NoteController, AbsenceController,
│   │   │   │                    # ModuleController, EdtController,
│   │   │   │                    # DocumentVerificationController
│   │   │   └── Auth/            # AuthenticatedSessionController, RegisteredUserController,
│   │   │                        # ParentAuthController, NewPasswordController, ...
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php   # Vérification rôle + compte actif
│   │       └── SetLocale.php        # Locale depuis la session
│   ├── Models/                  # 21 modèles Eloquent
│   ├── Services/
│   │   ├── AI/
│   │   │   ├── ChatbotService.php    # Client Groq API avec fallback
│   │   │   └── PromptBuilder.php     # Prompt contextuel avec données étudiant
│   │   └── CryptoSignatureService.php # RSA 2048 bits : sign + verify
│   └── Jobs/
│       └── SendNightlyAIAlerts.php   # Alertes IA nocturnes (queue)
├── database/
│   ├── migrations/            # 33 migrations
│   └── seeders/               # 12 seeders
├── lang/
│   ├── fr.json                # 430+ clés
│   ├── en.json
│   └── ar.json                # RTL
├── resources/views/
│   ├── layouts/
│   │   └── dashboard.blade.php  # Layout principal : sidebar, topbar, dark mode, flash
│   ├── admin/                 # Vues administration (10 modules)
│   ├── professeur/            # Vues professeur (7 modules)
│   ├── etudiant/              # Vues étudiant (7 modules)
│   ├── parent/                # Dashboard parent + chatbot intégré
│   ├── pdf/                   # Templates PDF (relevé de notes, demandes)
│   ├── emails/                # Templates emails (approbation, refus, justificatifs)
│   └── errors/                # Pages 403, 404, 500 personnalisées
└── routes/
    ├── web.php                # Routes web (auth + 4 rôles)
    ├── auth.php               # Routes auth avec throttle
    └── api.php                # Routes API (Sanctum)
```

---

## Routes importantes

### Authentification
| Méthode | URL | Throttle |
|---|---|---|
| POST | `/login` | 5/min |
| POST | `/login/parent` | 5/min |
| POST | `/register` | 10/min |
| GET | `/lang/{locale}` | — |

### Admin (`/admin/*`, middleware: `auth`, `role:admin`)
| URL | Description |
|---|---|
| `/admin/dashboard` | Tableau de bord |
| `/admin/users` | Gestion utilisateurs |
| `/admin/filieres/{id}/niveaux` | AJAX niveaux par filière |
| `/admin/niveaux/{id}/groupes` | AJAX groupes par niveau |
| `/admin/edt` + `/admin/edt/data` | Emploi du temps FullCalendar |
| `/admin/statistiques` | Statistiques globales |
| `/admin/demandes/{id}/pdf` | PDF signé + QR code |

### Professeur (`/professeur/*`, middleware: `auth`, `role:professeur`)
| URL | Description |
|---|---|
| `/professeur/notes/{module}/{groupe}` | Saisie des notes |
| `/professeur/absences/{seance}` | Feuille de présence |
| `/professeur/classroom/{module}/supports` | Upload support de cours |

### Étudiant (`/etudiant/*`, middleware: `auth`, `role:etudiant`)
| URL | Description |
|---|---|
| `/etudiant/releve-notes/download` | Relevé PDF signé |
| `/etudiant/justificatifs` | Soumettre justificatif |
| `/etudiant/demandes` | Demandes administratives |

### Parent (`/parent/*`, middleware: `auth`, `role:parent`)
| URL | Throttle | Description |
|---|---|---|
| `/parent/dashboard` | — | Tableau de bord |
| `/parent/chatbot` | 20/1440min | Envoyer message IA |
| `/parent/chatbot/history` | — | Historique conversations |
| `/parent/notifications` | — | Alertes JSON |

---

## Chatbot IA

Le chatbot utilise l'API **Groq** (modèle `llama-3.3-70b-versatile`) pour fournir des réponses contextuelles aux parents.

### Fonctionnement
1. Le `PromptBuilder` construit un prompt système avec les données réelles de l'étudiant (notes, absences, groupe, filière, demandes).
2. L'historique de conversation (max 10 messages) est envoyé pour maintenir le contexte.
3. Les conversations sont persistées en base de données (`chatbot_conversations`).
4. Timeout API : 30 secondes, fallback message générique en cas d'échec.

### Limites de sécurité
- 20 questions/jour par parent (cache applicatif + throttle HTTP middleware)
- Un parent ne peut accéder qu'aux données de son propre étudiant (vérification email `+parent`)
- Fallback systématique si API timeout ou erreur

### Alertes nocturnes (`SendNightlyAIAlerts`)
Le job analyse chaque soir le dossier de chaque étudiant et crée une `NotificationApp` (info/warning/urgent) si l'IA détecte quelque chose de signalé. Chaque parent est traité dans un `try/catch` indépendant.

Pour activer la planification, ajouter dans `routes/console.php` :
```php
Schedule::job(new \App\Jobs\SendNightlyAIAlerts())->dailyAt('22:00');
```

---

## Signature cryptographique des documents

Les relevés de notes et documents administratifs sont signés numériquement (RSA 2048 bits + SHA-256).

### Processus de signature
1. Les données du document sont sérialisées en JSON canonique (`JSON_UNESCAPED_UNICODE`)
2. Hash SHA-256 calculé sur le JSON
3. La clé privée UPF signe le hash (`openssl_sign`)
4. La signature base64, le hash et les données scellées sont stockés dans `document_signatures`
5. Un QR code SVG est généré pointant vers l'URL de vérification

### Vérification publique
```
GET /api/document-verification/{document_id}
```

---

## Internationalisation (i18n)

| Langue | Fichier | RTL |
|---|---|---|
| Français | `lang/fr.json` | Non |
| Anglais | `lang/en.json` | Non |
| Arabe | `lang/ar.json` | Oui (automatique) |

Changer de langue : cliquer sur le sélecteur dans la topbar, ou `GET /lang/{fr|en|ar}`.
Le layout applique automatiquement `dir="rtl"` pour l'arabe.

---

## Déploiement en production

### Variables d'environnement critiques
```dotenv
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
QUEUE_CONNECTION=database
GROQ_API_KEY=gsk_xxx
```

### Commandes de déploiement
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan migrate --force
php artisan storage:link
npm run build
```

### Queue et cron
```bash
# Supervisor pour la queue
php artisan queue:work --sleep=3 --tries=3

# Cron (cPanel ou crontab)
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

---

## Auteur

**Amine NAHLI**
Email : nahliamine95@gmail.com

---

## Licence

MIT — Voir `composer.json` pour les détails.

---

*Développé dans le cadre de l'examen TW S6 — Université Privée de Fès 2025-2026*
