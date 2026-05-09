<?php

namespace App\Mail;

use App\Models\Justificatif;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificatifRefuse extends Mailable
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
            subject: '⚠️ Mise à jour concernant votre justificatif d\'absence - UPF Gestion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.justificatifs.refuse',
        );
    }
}
