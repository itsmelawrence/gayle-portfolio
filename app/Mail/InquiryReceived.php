<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name: string, email: string, company: ?string, service: string, details: string}  $inquiry
     */
    public function __construct(public array $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->inquiry['email'], $this->inquiry['name'])],
            subject: 'New inquiry: '.$this->inquiry['service'],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.inquiry');
    }
}
