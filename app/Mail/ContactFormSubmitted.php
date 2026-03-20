<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject
            ? '[İletişim] '.$this->contactMessage->subject
            : '[İletişim] Yeni mesaj';

        return new Envelope(
            subject: $subject,
            replyTo: array_filter([
                $this->contactMessage->email,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-form-submitted',
        );
    }
}
