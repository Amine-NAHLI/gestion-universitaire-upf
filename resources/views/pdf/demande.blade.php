<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $demande->type }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 20px;
            margin-bottom: 50px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        .univ-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .address {
            font-size: 10px;
            color: #666;
        }
        .document-title {
            text-align: center;
            text-decoration: underline;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 60px;
            text-transform: uppercase;
        }
        .content {
            margin-bottom: 100px;
            font-size: 14pt;
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
        }
        .date-place {
            text-align: right;
            margin-bottom: 40px;
        }
        .signature {
            text-align: right;
            margin-right: 50px;
            font-weight: bold;
        }
        .signature-name {
            margin-top: 80px;
        }
        @page {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UPF</div>
        <div class="univ-name">Université Privée de Fès</div>
        <div class="address">
            Route d'Imouzzer, Fès, Maroc<br>
            Tél : +212 5 35 60 80 80 | Email : contact@upf.ma
        </div>
    </div>

    <div class="document-title">
        @php
            $titles = [
                'attestation_scolarite' => 'Attestation de Scolarité',
                'releve_notes' => 'Relevé de Notes',
                'certificat_inscription' => 'Certificat d\'Inscription',
                'attestation_travail' => 'Attestation de Travail',
                'ordre_mission' => 'Ordre de Mission',
            ];
            $roleLabel = ($demande->user->role === 'etudiant') ? 'étudiant(e) inscrit(e)' : 'membre du corps professoral';
        @endphp
        {{ $titles[$demande->type] ?? 'Document Administratif' }}
    </div>

    <div class="content">
        <p>Nous soussignés, Direction de l'Université Privée de Fès, certifions par la présente que :</p>
        
        <p style="margin-left: 40px;">
            <strong>M./Mme :</strong> {{ strtoupper($demande->user->name) }} {{ $demande->user->prenom }}<br>
            @if($demande->user->role === 'etudiant')
                <strong>N° Apogée :</strong> {{ $demande->user->etudiant->num_apogee ?? 'En cours' }}<br>
                <strong>Filière :</strong> {{ $demande->user->etudiant->filiere->nom ?? 'N/A' }}
            @endif
        </p>

        <p>Est bien {{ $roleLabel }} au sein de notre établissement pour l'année universitaire 2025-2026.</p>

        <p>Cette attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>

    <div class="footer">
        <div class="date-place">
            Fait à Fès, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}
        </div>
        
        <div class="signature">
            Le Directeur des Études<br>
            <div class="signature-name">(Signature et Cachet)</div>
        </div>
    </div>
</body>
</html>
