# 📚 Documentation de l'API REST — UPF Gestion

Cette API permet d'interagir avec le système de gestion universitaire. Elle est sécurisée par **Laravel Sanctum** via des jetons d'accès personnels (Bearer Tokens).

---

## 🔐 Authentification

Tous les endpoints (sauf `/login` et `/register`) nécessitent l'en-tête suivant :
`Authorization: Bearer {votre_token}`

### 1. Connexion (Login)
**POST** `/api/login`  
Obtenir un jeton d'accès.

- **Body (JSON) :**
  ```json
  {
    "email": "etudiant@upf.ac.ma",
    "password": "password"
  }
  ```
- **Réponse (200 OK) :**
  ```json
  {
    "message": "Connexion réussie.",
    "token": "1|abc123token...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "nom": "NAHLI",
      "prenom": "Amine",
      "email": "etudiant@upf.ac.ma",
      "role": "etudiant"
    }
  }
  ```
- **Erreurs :** 401 (Identifiants incorrects), 422 (Validation échouée).

### 2. Déconnexion (Logout)
**POST** `/api/logout`  
Révise le jeton d'accès actuel.

- **Réponse (200 OK) :**
  ```json
  { "message": "Déconnexion réussie." }
  ```

### 3. Profil Utilisateur (Me)
**GET** `/api/me`  
Récupérer les informations de l'utilisateur connecté.

- **Réponse (200 OK) :**
  ```json
  {
    "id": 1,
    "nom": "NAHLI",
    "prenom": "Amine",
    "email": "etudiant@upf.ac.ma",
    "role": "etudiant",
    "is_active": true
  }
  ```

---

## 🎓 Données Académiques

### 4. Mes Notes (Notes)
**GET** `/api/notes`  
Récupérer les résultats académiques.

- **Réponse Étudiant (200 OK) :**
  ```json
  {
    "annee_universitaire": "2025-2026",
    "moyenne_generale": 14.5,
    "notes": [
      {
        "module": "Algorithmique",
        "code": "M101",
        "cc1": 12,
        "cc2": 15,
        "examen": 16,
        "note_finale": 14.8,
        "statut": "validé"
      }
    ]
  }
  ```
- **Réponse Professeur (200 OK) :** Résumé des moyennes par module enseigné.

### 5. Emploi du Temps (EDT)
**GET** `/api/edt`  
Récupérer le planning des séances.

- **Réponse (200 OK) :**
  ```json
  {
    "total": 12,
    "seances": [
      {
        "id": 45,
        "date": "2026-05-10",
        "heure_debut": "09:00:00",
        "heure_fin": "11:00:00",
        "type": "cours",
        "statut": "planifiee",
        "module": "Développement Web",
        "code_module": "WEB2",
        "salle": "Amphi A",
        "groupe": "G1",
        "professeur": "Jean DUPONT"
      }
    ]
  }
  ```

### 6. Suivi des Absences
**GET** `/api/absences`  
Récupérer l'historique des absences (Étudiants uniquement).

- **Réponse (200 OK) :**
  ```json
  {
    "total": 2,
    "justifiees": 1,
    "non_justifiees": 1,
    "absences": [
      {
        "id": 12,
        "module": "Mathématiques",
        "date": "2026-05-08",
        "heure": "14:00 - 16:00",
        "justifiee": false,
        "statut_justificatif": "en_attente"
      }
    ]
  }
  ```

---

## 🏛️ Référentiel

### 7. Liste des Modules
**GET** `/api/modules`  
Liste de tous les modules disponibles dans l'établissement.

- **Réponse (200 OK) :**
  ```json
  {
    "total": 45,
    "modules": [
      {
        "id": 1,
        "nom": "Base de données",
        "code": "DB1",
        "coefficient": 2,
        "heures_cours": 20,
        "filiere": "Informatique",
        "niveau": "Bac+2"
      }
    ]
  }
  ```

### 8. Détails d'un Module
**GET** `/api/modules/{id}`  
Informations complètes sur un module spécifique.

- **Réponse (200 OK) :**
  ```json
  {
    "id": 1,
    "nom": "Base de données",
    "code": "DB1",
    "description": "Apprentissage SQL et modélisation...",
    "heures": { "cours": 20, "td": 10, "tp": 10 },
    "filiere": "Informatique",
    "professeurs": ["Ahmed BENANI"],
    "groupes": ["G1", "G2"]
  }
  ```

---

## ⚠️ Codes de Statut HTTP

| Code | Description |
|---|---|
| **200** | Succès |
| **201** | Ressource créée avec succès |
| **401** | Non authentifié (Token manquant ou invalide) |
| **403** | Accès refusé (Droits insuffisants) |
| **404** | Ressource introuvable |
| **422** | Erreur de validation des données |
| **500** | Erreur interne du serveur |
