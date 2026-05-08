<?php

namespace App\Mail;

use App\Models\DemandeAdministrative;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeRefusee extends Mailable
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
            subject: 'Mise à jour concernant votre demande administrative - UPF Gestion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demandes.refusee',
        );
    }
}
