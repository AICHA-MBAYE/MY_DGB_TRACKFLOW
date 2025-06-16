<?php

namespace App\Mail;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL; // Pour générer une URL signée si nécessaire

class AgentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agent;
    public $rejectionReason; // Raison du rejet (optionnel, mais utile)

    /**
     * Crée une nouvelle instance de message.
     */
    public function __construct(Agent $agent, $rejectionReason = null)
    {
        $this->agent = $agent;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Récupère l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informations concernant votre inscription à MY_DGB_TRACKFLOW',
        );
    }

    /**
     * Récupère la définition du contenu du message.
     */
    public function content(): Content
    {
        // Générer une URL pour la modification de l'inscription rejetée.
        // Nous utiliserons l'ID de l'agent dans l'URL. Dans un environnement réel,
        // une URL signée (URL::signedRoute()) serait plus sécurisée.
        $editUrl = route('agent.edit_rejected_form', ['agent' => $this->agent->id]);

        return new Content(
            html: 'emails.agent-rejected', // Pointe vers la vue Blade pour l'email
            with: [
                'agent' => $this->agent,
                'rejectionReason' => $this->rejectionReason,
                'editUrl' => $editUrl, // Passe l'URL à la vue de l'email
            ]
        );
    }

    /**
     * Récupère les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
