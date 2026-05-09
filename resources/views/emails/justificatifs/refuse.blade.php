<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #ef4444; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; font-size: 0.8em; color: #6b7280; margin-top: 20px; }
        .motif { background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 15px 0; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UPF Gestion — Justificatif Refusé</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $justificatif->absence->etudiant->user->prenom }},</h2>
        <p>Votre justificatif pour l'absence du <strong>{{ $justificatif->absence->seance->date->format('d/m/Y') }}</strong> ({{ $justificatif->absence->seance->module->nom }}) a été <strong>refusé</strong>.</p>
        
        <div class="motif">
            <strong>Motif du refus :</strong><br>
            {{ $justificatif->motif_refus }}
        </div>

        <p>Nous vous invitons à vous rapprocher du service administratif pour plus d'informations.</p>
        <p>Cordialement,<br>Le service administratif de l'UPF</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès.</p>
    </div>
</body>
</html>
