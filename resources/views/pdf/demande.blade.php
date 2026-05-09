<!DOCTYPE html>
<html lang="{{ $langue ?? 'fr' }}" dir="{{ ($langue ?? 'fr') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $demande->type }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            color: #333; line-height: 1.6; margin: 0; padding: 40px; 
            direction: {{ ($langue ?? 'fr') === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ ($langue ?? 'fr') === 'ar' ? 'right' : 'left' }};
        }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 32px; font-weight: bold; color: #1e3a8a; margin-bottom: 5px; }
        .univ-name { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .address { font-size: 10px; color: #666; }
        .document-title { text-align: center; text-decoration: underline; font-size: 20px; font-weight: bold; margin-bottom: 40px; text-transform: uppercase; }
        .content { margin-bottom: 50px; font-size: 13pt; text-align: justify; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 10pt; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: {{ ($langue ?? 'fr') === 'ar' ? 'right' : 'left' }}; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 50px; }
        .date-place { text-align: {{ ($langue ?? 'fr') === 'ar' ? 'left' : 'right' }}; margin-bottom: 30px; font-size: 11pt; }
        .signature { text-align: {{ ($langue ?? 'fr') === 'ar' ? 'left' : 'right' }}; margin-{{ ($langue ?? 'fr') === 'ar' ? 'left' : 'right' }}: 50px; font-weight: bold; }
        .signature-name { margin-top: 60px; font-size: 10pt; }
    </style>
</head>
<body>
    @php
        $lang = $langue ?? ($demande->langue_document ?? 'fr');

        // --- Dictionnaire de traductions ---
        $t = [
            'fr' => [
                'univ' => 'Université Privée de Fès',
                'address' => "Route d'Imouzzer, Fès, Maroc | Tél : +212 5 35 60 80 80",
                'attestation_scolarite' => 'Attestation de Scolarité',
                'releve_notes' => 'Relevé de Notes',
                'certificat_inscription' => "Certificat d'Inscription",
                'attestation_travail' => 'Attestation de Travail',
                'ordre_mission' => 'Ordre de Mission',
                'default_title' => 'Document Administratif',
                'certify_student' => "La Direction de l'Université Privée de Fès certifie que l'étudiant(e) :",
                'mr_mrs' => 'M./Mme',
                'apogee' => 'N° Apogée',
                'filiere' => 'Filière',
                'niveau' => 'Niveau',
                'groupe' => 'Groupe',
                'enrolled' => "Est régulièrement inscrit(e) au sein de notre établissement au titre de l'année universitaire",
                'transcript_of' => "Relevé de notes officiel de l'étudiant(e) :",
                'module' => 'Module',
                'average' => 'Moyenne',
                'general_avg' => 'Moyenne Générale',
                'certify_enrolled' => "Nous certifions que l'étudiant(e)",
                'enrolled_in' => "est inscrit(e) en :",
                'for_year' => "Pour l'année universitaire",
                'certify_prof' => "La Direction de l'Université Privée de Fès certifie que :",
                'grade' => 'Grade',
                'specialty' => 'Spécialité',
                'member_since' => "Est membre du corps professoral de notre établissement depuis le",
                'mission_for' => "Ordre de mission délivré à :",
                'destination' => 'Destination',
                'period' => 'Période',
                'from' => 'Du',
                'to' => 'au',
                'purpose' => 'Objet de la mission',
                'authorized' => "L'intéressé(e) est autorisé(e) à se déplacer pour les besoins du service.",
                'closing' => "Cette attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.",
                'done_at' => 'Fait à Fès, le',
                'direction' => 'La Direction Générale',
                'stamp' => '(Signature et Cachet)',
            ],
            'en' => [
                'univ' => 'Private University of Fez',
                'address' => "Route d'Imouzzer, Fez, Morocco | Phone: +212 5 35 60 80 80",
                'attestation_scolarite' => 'Certificate of Enrollment',
                'releve_notes' => 'Official Transcript',
                'certificat_inscription' => 'Registration Certificate',
                'attestation_travail' => 'Employment Certificate',
                'ordre_mission' => 'Mission Order',
                'default_title' => 'Administrative Document',
                'certify_student' => 'The Administration of the Private University of Fez certifies that the student:',
                'mr_mrs' => 'Mr./Ms.',
                'apogee' => 'Student ID',
                'filiere' => 'Major',
                'niveau' => 'Level',
                'groupe' => 'Group',
                'enrolled' => 'Is officially enrolled in our institution for the academic year',
                'transcript_of' => 'Official transcript for the student:',
                'module' => 'Course',
                'average' => 'Average',
                'general_avg' => 'General Average',
                'certify_enrolled' => 'We certify that the student',
                'enrolled_in' => 'is enrolled in:',
                'for_year' => 'For the academic year',
                'certify_prof' => 'The Administration of the Private University of Fez certifies that:',
                'grade' => 'Rank',
                'specialty' => 'Specialty',
                'member_since' => 'Has been a faculty member of our institution since',
                'mission_for' => 'Mission order issued to:',
                'destination' => 'Destination',
                'period' => 'Period',
                'from' => 'From',
                'to' => 'to',
                'purpose' => 'Purpose of Mission',
                'authorized' => 'The aforementioned person is authorized to travel for professional duties.',
                'closing' => 'This certificate is issued upon request for whatever purpose it may serve.',
                'done_at' => 'Done at Fez, on',
                'direction' => 'General Administration',
                'stamp' => '(Signature and Stamp)',
            ],
            'ar' => [
                'univ' => 'الجامعة الخاصة بفاس',
                'address' => 'طريق إيموزار، فاس، المغرب | الهاتف: +212 5 35 60 80 80',
                'attestation_scolarite' => 'شهادة التمدرس',
                'releve_notes' => 'كشف النقاط',
                'certificat_inscription' => 'شهادة التسجيل',
                'attestation_travail' => 'شهادة العمل',
                'ordre_mission' => 'أمر بمهمة',
                'default_title' => 'وثيقة إدارية',
                'certify_student' => 'تشهد إدارة الجامعة الخاصة بفاس أن الطالب(ة):',
                'mr_mrs' => 'السيد(ة)',
                'apogee' => 'رقم أبوجي',
                'filiere' => 'الشعبة',
                'niveau' => 'المستوى',
                'groupe' => 'الفوج',
                'enrolled' => 'مسجل(ة) بانتظام في مؤسستنا برسم السنة الجامعية',
                'transcript_of' => 'كشف النقاط الرسمي للطالب(ة):',
                'module' => 'الوحدة',
                'average' => 'المعدل',
                'general_avg' => 'المعدل العام',
                'certify_enrolled' => 'نشهد أن الطالب(ة)',
                'enrolled_in' => 'مسجل(ة) في:',
                'for_year' => 'برسم السنة الجامعية',
                'certify_prof' => 'تشهد إدارة الجامعة الخاصة بفاس أن:',
                'grade' => 'الرتبة',
                'specialty' => 'التخصص',
                'member_since' => 'عضو في هيئة التدريس بمؤسستنا منذ',
                'mission_for' => 'أمر بمهمة صادر لفائدة:',
                'destination' => 'الوجهة',
                'period' => 'الفترة',
                'from' => 'من',
                'to' => 'إلى',
                'purpose' => 'موضوع المهمة',
                'authorized' => 'يُؤذن للمعني(ة) بالأمر بالتنقل لأغراض الخدمة.',
                'closing' => 'سلمت هذه الشهادة للمعني(ة) بالأمر لاستعمالها فيما يتطلبه القانون.',
                'done_at' => 'حُرر بفاس، في',
                'direction' => 'الإدارة العامة',
                'stamp' => '(التوقيع والختم)',
            ],
        ];
        $tr = $t[$lang] ?? $t['fr'];
        $user = $demande->user;
    @endphp

    <div class="header">
        <div class="logo">UPF</div>
        <div class="univ-name">{{ $tr['univ'] }}</div>
        <div class="address">{{ $tr['address'] }}</div>
    </div>

    <div class="document-title">
        {{ $tr[$demande->type] ?? $tr['default_title'] }}
    </div>

    <div class="content">
        @if($demande->type === 'attestation_scolarite')
            <p>{{ $tr['certify_student'] }}</p>
            <p style="margin-left: 30px;">
                <strong>{{ $tr['mr_mrs'] }} :</strong> {{ strtoupper($user->name) }} {{ $user->prenom }}<br>
                <strong>{{ $tr['apogee'] }} :</strong> {{ $user->etudiant->num_apogee ?? '---' }}<br>
                <strong>{{ $tr['filiere'] }} :</strong> {{ $user->etudiant->groupe->niveau->filiere->nom ?? '---' }}
            </p>
            <p>{{ $tr['enrolled'] }} <strong>2025-2026</strong>.</p>
        
        @elseif($demande->type === 'releve_notes')
            <p>{{ $tr['transcript_of'] }} <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong></p>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $tr['module'] }}</th>
                        <th>CC1</th>
                        <th>CC2</th>
                        <th>Examen</th>
                        <th>{{ $tr['average'] }}</th>
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
            <p style="margin-top: 20px;">{{ $tr['general_avg'] }} : <strong>{{ round($notes->avg('note_finale'), 2) }} / 20</strong></p>

        @elseif($demande->type === 'certificat_inscription')
            <p>{{ $tr['certify_enrolled'] }} <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong> {{ $tr['enrolled_in'] }}</p>
            <p style="margin-left: 30px;">
                <strong>{{ $tr['filiere'] }} :</strong> {{ $user->etudiant->groupe->niveau->filiere->nom ?? '---' }}<br>
                <strong>{{ $tr['niveau'] }} :</strong> {{ $user->etudiant->groupe->niveau->nom ?? '---' }}<br>
                <strong>{{ $tr['groupe'] }} :</strong> {{ $user->etudiant->groupe->nom ?? '---' }}
            </p>
            <p>{{ $tr['for_year'] }} 2025-2026.</p>

        @elseif($demande->type === 'attestation_travail')
            <p>{{ $tr['certify_prof'] }}</p>
            <p style="margin-left: 30px;">
                <strong>{{ $tr['mr_mrs'] }} :</strong> {{ strtoupper($user->name) }} {{ $user->prenom }}<br>
                <strong>{{ $tr['grade'] }} :</strong> {{ $user->professeur->grade ?? 'Enseignant' }}<br>
                <strong>{{ $tr['specialty'] }} :</strong> {{ $user->professeur->specialite ?? 'N/A' }}
            </p>
            <p>{{ $tr['member_since'] }} {{ $user->professeur->date_embauche ? $user->professeur->date_embauche->format('d/m/Y') : '---' }}.</p>

        @elseif($demande->type === 'ordre_mission')
            <p>{{ $tr['mission_for'] }} <strong>{{ strtoupper($user->name) }} {{ $user->prenom }}</strong></p>
            <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                <p><strong>{{ $tr['destination'] }} :</strong> {{ $demande->donnees_supplementaires['destination'] ?? '---' }}</p>
                <p><strong>{{ $tr['period'] }} :</strong> {{ $tr['from'] }} {{ $demande->donnees_supplementaires['date_depart'] ?? '---' }} {{ $tr['to'] }} {{ $demande->donnees_supplementaires['date_retour'] ?? '---' }}</p>
                <p><strong>{{ $tr['purpose'] }} :</strong> {{ $demande->donnees_supplementaires['motif'] ?? '---' }}</p>
            </div>
            <p>{{ $tr['authorized'] }}</p>
        @endif

        <p style="margin-top: 30px;">{{ $tr['closing'] }}</p>
    </div>

    <div class="footer">
        @php
            $dateLocales = ['fr' => 'fr', 'en' => 'en', 'ar' => 'ar'];
            $loc = $dateLocales[$lang] ?? 'fr';
        @endphp
        <div class="date-place">{{ $tr['done_at'] }} {{ now()->locale($loc)->isoFormat('D MMMM YYYY') }}</div>
        <div class="signature">
            {{ $tr['direction'] }}<br>
            <div class="signature-name">{{ $tr['stamp'] }}</div>
        </div>
    </div>
</body>
</html>
