<?php

namespace App\Mail;

use App\Models\DemandeAdministrative;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouvelleDemandeAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(DemandeAdministrative $demande)
    {
        $this->demande = $demande;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Nouvelle demande administrative reçue - UPF Gestion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.nouvelle_demande',
        );
    }
}
