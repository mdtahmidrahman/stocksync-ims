<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $companyName;
    public $loginUrl;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $companyName, string $loginUrl, ?string $temporaryPassword = null)
    {
        $this->user = $user;
        $this->companyName = $companyName;
        $this->loginUrl = $loginUrl;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to join {$this->companyName} on StockSync IMS",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_invitation',
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
