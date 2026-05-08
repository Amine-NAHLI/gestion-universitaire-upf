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
        <h1>UPF Gestion — Université Privée de Fès</h1>
    </div>
    <div class="content">
        <h2>Bonjour {{ $demande->user->prenom }},</h2>
        <p>Nous avons traité votre demande de document administratif ({{ str_replace('_', ' ', ucwords($demande->type, '_')) }}).</p>
        
        <p>Malheureusement, votre demande ne peut pas être satisfaite pour la raison suivante :</p>
        
        <div class="motif">
            <strong>Motif du refus :</strong><br>
            {{ $demande->motif_refus }}
        </div>

        <p>Nous vous invitons à consulter votre portail pour plus de détails ou à soumettre une nouvelle demande en tenant compte du motif mentionné.</p>

        <p>Cordialement,<br>Le service administratif de l'UPF</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès. Tous droits réservés.</p>
    </div>
</body>
</html>
