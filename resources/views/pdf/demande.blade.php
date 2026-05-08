<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $demande->type }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #333; line-height: 1.6; margin: 0; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 32px; font-weight: bold; color: #1e3a8a; margin-bottom: 5px; }
        .univ-name { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .address { font-size: 10px; color: #666; }
        .document-title { text-align: center; text-decoration: underline; font-size: 20px; font-weight: bold; margin-bottom: 40px; text-transform: uppercase; }
        .content { margin-bottom: 50px; font-size: 13pt; text-align: justify; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 10pt; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 50px; }
        .date-place { text-align: right; margin-bottom: 30px; font-size: 11pt; }
        .signature { text-align: right; margin-right: 50px; font-weight: bold; }
        .signature-name { margin-top: 60px; font-size: 10pt; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">UPF</div>
        <div class="univ-name">Université Privée de Fès</div>
        <div class="address">Route d'Imouzzer, Fès, Maroc | Tél : +212 5 35 60 80 80</div>
    </div>

    @php
        $titles = [
            'attestation_scolarite' => 'Attestation de Scolarité',
            'releve_notes' => 'Relevé de Notes',
            'certificat_inscription' => 'Certificat d\'Inscription',
            'attestation_travail' => 'Attestation de Travail',
            'ordre_mission' => 'Ordre de Mission',
        ];
        $user = $demande->user;
    @endphp

    <div class="document-title">
        {{ $titles[$demande->type] ?? 'Document Administratif' }}
    </div>

    <div class="content">
        @if($demande->type === 'attestation_scolarite')
            <p>La Direction de l'Université Privée de Fès certifie que l'étudiant(e) :</p>
            <p style="margin-left: 30px;">
                <strong>M./Mme :</strong> {{ strtoupper($user->name) }} {{ $user->prenom }}<br>
                <strong>N° Apogée :</strong> {{ $user->etudiant->num_apogee ?? '---' }}<br>
                <strong>Filière :</strong> {{ $user->etudiant->groupe->niveau->filiere->nom ?? '---' }}
            </p>
            <p>Est régulièrement inscrit(e) au sein de notre établissement au titre de l'année universitaire <strong>2025-2026</strong>.</p>
        
        @elseif($demande->type === 'releve_notes')
            <p>Relevé de notes officiel de l'étudiant(e) : <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong></p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>CC1</th>
                        <th>CC2</th>
                        <th>Examen</th>
                        <th>Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @php $notes = $user->etudiant->notes()->with('module')->get(); @endphp
                    @foreach($notes as $note)
                        <tr>
                            <td>{{ $note->module->nom }}</td>
                            <td>{{ $note->cc1 ?? '--' }}</td>
                            <td>{{ $note->cc2 ?? '--' }}</td>
                            <td>{{ $note->examen ?? '--' }}</td>
                            <td><strong>{{ $note->note_finale ?? '--' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="margin-top: 20px;">Moyenne Générale : <strong>{{ round($notes->avg('note_finale'), 2) }} / 20</strong></p>

        @elseif($demande->type === 'certificat_inscription')
            <p>Nous certifions que l'étudiant(e) <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong> est inscrit(e) en :</p>
            <p style="margin-left: 30px;">
                <strong>Filière :</strong> {{ $user->etudiant->groupe->niveau->filiere->nom ?? '---' }}<br>
                <strong>Niveau :</strong> {{ $user->etudiant->groupe->niveau->nom ?? '---' }}<br>
                <strong>Groupe :</strong> {{ $user->etudiant->groupe->nom ?? '---' }}
            </p>
            <p>Pour l'année universitaire 2025-2026.</p>

        @elseif($demande->type === 'attestation_travail')
            <p>La Direction de l'Université Privée de Fès certifie que :</p>
            <p style="margin-left: 30px;">
                <strong>M./Mme :</strong> {{ strtoupper($user->name) }} {{ $user->prenom }}<br>
                <strong>Grade :</strong> {{ $user->professeur->grade ?? 'Enseignant' }}<br>
                <strong>Spécialité :</strong> {{ $user->professeur->specialite ?? 'N/A' }}
            </p>
            <p>Est membre du corps professoral de notre établissement depuis le {{ $user->professeur->date_embauche ? $user->professeur->date_embauche->format('d/m/Y') : '---' }}.</p>

        @elseif($demande->type === 'ordre_mission')
            <p>Ordre de mission délivré à : <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong></p>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                <p><strong>Destination :</strong> {{ $demande->donnees_supplementaires['destination'] ?? '---' }}</p>
                <p><strong>Période :</strong> Du {{ $demande->donnees_supplementaires['date_depart'] ?? '---' }} au {{ $demande->donnees_supplementaires['date_retour'] ?? '---' }}</p>
                <p><strong>Objet de la mission :</strong> {{ $demande->donnees_supplementaires['motif'] ?? '---' }}</p>
            </div>
            <p>L'intéressé(e) est autorisé(e) à se déplacer pour les besoins du service.</p>
        @endif

        <p style="margin-top: 30px;">Cette attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>

    <div class="footer">
        <div class="date-place">Fait à Fès, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
        <div class="signature">
            La Direction Générale<br>
            <div class="signature-name">(Signature et Cachet)</div>
        </div>
    </div>
</body>
</html>
