<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; font-size: 0.8em; color: #6b7280; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UPF Gestion — Nouveau Justificatif</h1>
    </div>
    <div class="content">
        <h2>Un nouveau justificatif d'absence a été déposé</h2>
        <p><strong>Détails de l'étudiant :</strong></p>
        <ul>
            <li>Nom : {{ $justificatif->absence->etudiant->user->full_name }}</li>
            <li>Date de l'absence : {{ $justificatif->absence->seance->date->format('d/m/Y') }}</li>
            <li>Module : {{ $justificatif->absence->seance->module->nom }}</li>
        </ul>
        
        <p>Merci de valider ou refuser ce justificatif sur le portail d'administration.</p>

        <a href="{{ url('/admin/absences') }}" class="btn">Gérer les absences</a>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès.</p>
    </div>
</body>
</html>
