<?php

namespace App\Mail;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentValidatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(Agent $agent, string $password)
    {
        $this->agent = $agent;
        $this->password = $password; // Le mot de passe en texte clair, à n'utiliser qu'une fois pour l'envoi initial
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre compte MY_DGB_TRACKFLOW a été validé !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.agent-validated', // Le fichier Blade pour le contenu de l'email
            with: [
                'agent' => $this->agent,
                'password' => $this->password,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}