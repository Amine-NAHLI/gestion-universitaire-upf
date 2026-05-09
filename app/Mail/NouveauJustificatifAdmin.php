<?php

namespace App\Mail;

use App\Models\Justificatif;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauJustificatifAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $justificatif;

    public function __construct(Justificatif $justificatif)
    {
        $this->justificatif = $justificatif;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📝 Nouveau justificatif d\'absence déposé - UPF Gestion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.nouveau_justificatif',
        );
    }
}
