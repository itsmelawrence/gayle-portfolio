<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->lead->email, $this->lead->name)],
            subject: 'New inquiry from '.$this->lead->name.' · '.$this->lead->service,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.inquiry',
            text: 'mail.inquiry-text',
            with: [
                'submittedAt' => $this->lead->created_at->timezone(config('portfolio.timezone')),
                'source' => array_filter([
                    'Campaign' => implode(' / ', array_filter([$this->lead->utm_source, $this->lead->utm_medium, $this->lead->utm_campaign])),
                    'Referred by' => $this->lead->referrer,
                    'Landing page' => $this->lead->landing_page,
                ]),
            ],
        );
    }
}
