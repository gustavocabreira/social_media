<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreatedUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('gustavo.softdev@gmail.com', 'Gustavo Cabreira'),
            subject: 'Welcome!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.created-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
