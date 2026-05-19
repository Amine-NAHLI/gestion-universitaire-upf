<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes - UPF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            padding: 20px 30px;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }
        .header .subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .student-info table {
            width: 100%;
        }
        .student-info td {
            padding: 3px 8px;
            vertical-align: top;
        }
        .student-info .label {
            font-weight: bold;
            color: #475569;
            width: 140px;
        }
        .student-info .value {
            color: #1e293b;
        }

        /* Notes Table */
        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .notes-table th {
            background: #4f46e5;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .notes-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .notes-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .notes-table .note-cell {
            text-align: center;
            font-weight: bold;
        }

        /* Average */
        .moyenne-box {
            text-align: right;
            margin-bottom: 25px;
            padding: 10px 15px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-radius: 6px;
            display: inline-block;
            float: right;
        }
        .moyenne-box .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .moyenne-box .value {
            font-size: 20px;
            font-weight: bold;
        }

        /* Footer Verification */
        .verification-footer {
            clear: both;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px dashed #cbd5e1;
            page-break-inside: avoid;
        }
        .verification-container {
            display: flex;
            align-items: flex-start;
        }
        .qr-section {
            float: left;
            width: 180px;
            text-align: center;
        }
        .qr-section img {
            width: 130px;
            height: 130px;
        }
        .qr-section .scan-text {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .crypto-section {
            margin-left: 190px;
            font-size: 9px;
            color: #64748b;
        }
        .crypto-section .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #ecfdf5;
            color: #059669;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .crypto-section .doc-id {
            font-family: monospace;
            font-size: 10px;
            color: #1e293b;
            font-weight: bold;
        }
        .crypto-section .hash-preview {
            font-family: monospace;
            font-size: 7px;
            color: #94a3b8;
            word-break: break-all;
            margin-top: 4px;
        }

        /* Stamp */
        .official-stamp {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Université Privée de Fès</h1>
        <h2>Relevé de Notes Officiel</h2>
        <div class="subtitle">Année Universitaire {{ date('Y') - 1 }}/{{ date('Y') }}</div>
    </div>

    <!-- Student Info -->
    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nom et Prénom :</td>
                <td class="value">{{ $sealedData['student_name'] }}</td>
                <td class="label">CNE :</td>
                <td class="value">{{ $sealedData['cne'] }}</td>
            </tr>
            <tr>
                <td class="label">Filière :</td>
                <td class="value">{{ $sealedData['filiere'] }}</td>
                <td class="label">Groupe :</td>
                <td class="value">{{ $sealedData['groupe'] }}</td>
            </tr>
            <tr>
                <td class="label">Date de délivrance :</td>
                <td class="value">{{ \Carbon\Carbon::parse($sealedData['issue_date'])->format('d/m/Y') }}</td>
                <td class="label">Document ID :</td>
                <td class="value" style="font-family: monospace; font-size: 10px;">{{ $docSignature->document_id }}</td>
            </tr>
        </table>
    </div>

    <!-- Notes Table -->
    <table class="notes-table">
        <thead>
            <tr>
                <th style="width: 50%;">Module</th>
                <th style="width: 25%; text-align: center;">Note / 20</th>
                <th style="width: 25%; text-align: center;">Coefficient</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sealedData['notes'] as $note)
            <tr>
                <td>{{ $note['module'] }}</td>
                <td class="note-cell">{{ $note['note'] }}</td>
                <td class="note-cell">{{ $note['coefficient'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Average -->
    <div class="moyenne-box">
        <div class="label">Moyenne Générale</div>
        <div class="value">{{ $sealedData['moyenne'] }}</div>
    </div>

    <!-- QR Code & Crypto Verification Footer -->
    <div class="verification-footer">
        <div class="qr-section">
            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Code de vérification">
            <div class="scan-text">Scannez pour vérifier l'authenticité</div>
        </div>
        <div class="crypto-section">
            <div class="badge">🔒 Document Scellé par Cryptographie Asymétrique</div>
            <br>
            <strong>Identifiant du document :</strong>
            <span class="doc-id">{{ $docSignature->document_id }}</span>
            <br><br>
            <strong>Algorithme :</strong> RSA-2048 + SHA-256<br>
            <strong>Émis le :</strong> {{ $docSignature->issued_at->format('d/m/Y à H:i') }}<br>
            <div class="hash-preview">
                SHA-256 : {{ $docSignature->data_hash }}
            </div>
            <br>
            <em>Ce document est protégé contre la falsification par une Infrastructure à Clés Publiques (PKI). 
            Toute modification invalide la signature cryptographique de l'Université Privée de Fès.</em>
        </div>
    </div>

    <!-- Official Stamp -->
    <div class="official-stamp">
        © {{ date('Y') }} Université Privée de Fès — Système de Certification Numérique — Ce document est généré automatiquement et ne nécessite pas de signature manuscrite.
    </div>

</body>
</html>
