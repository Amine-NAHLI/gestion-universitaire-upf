<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; font-size: 0.8em; color: #6b7280; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #1e3a8a; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UPF Gestion — Nouvelle Demande</h1>
    </div>
    <div class="content">
        <h2>Une nouvelle demande administrative a été soumise</h2>
        <p><strong>Détails du demandeur :</strong></p>
        <ul>
            <li>Nom : {{ $demande->user->full_name }}</li>
            <li>Rôle : {{ ucfirst($demande->user->role) }}</li>
            <li>Type de demande : {{ str_replace('_', ' ', ucwords($demande->type, '_')) }}</li>
        </ul>
        
        <p>Merci de traiter cette demande sur le portail d'administration.</p>

        <a href="{{ url('/admin/demandes') }}" class="btn">Gérer les demandes</a>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Université Privée de Fès.</p>
    </div>
</body>
</html>
