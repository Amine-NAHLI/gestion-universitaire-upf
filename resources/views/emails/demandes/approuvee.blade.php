<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; font-size: 0.8em; color: #6b7280; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UPF Gestion — Université Privée de Fès</h1>
    </div>
    <div class="content">
        <h2>Félicitations, {{ $demande->user->full_name }} !</h2>
        <p>Nous avons le plaisir de vous informer que votre demande de document administratif a été <strong>approuvée</strong>.</p>
        
        <p><strong>Détails de la demande :</strong></p>
        <ul>
            <li>Type : {{ str_replace('_', ' ', ucwords($demande->type, '_')) }}</li>
            <li>Date de demande : {{ $demande->created_at->format('d/m/Y') }}</li>
        </ul>

        <p>Le document est désormais disponible en téléchargement sur votre portail utilisateur.</p>

        <a href="{{ url('/login') }}" class="btn">Accéder à mon portail</a>
        
        <p>Cordialement,<br>Le service administratif de l'UPF</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès. Tous droits réservés.</p>
    </div>
</body>
</html>
