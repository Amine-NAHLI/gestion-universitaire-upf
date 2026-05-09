<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; font-size: 0.8em; color: #6b7280; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UPF Gestion — Justificatif Accepté</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $justificatif->absence->etudiant->user->prenom }},</h2>
        <p>Nous vous informons que votre justificatif pour l'absence du <strong>{{ $justificatif->absence->seance->date->format('d/m/Y') }}</strong> ({{ $justificatif->absence->seance->module->nom }}) a été <strong>accepté</strong>.</p>
        <p>Votre absence a été marquée comme justifiée dans votre dossier académique.</p>
        <p>Cordialement,<br>Le service administratif de l'UPF</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès.</p>
    </div>
</body>
</html>
