<?php

namespace Tests\Feature;

use App\Mail\InquiryReceived;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    private array $inquiry = [
        'name' => 'Sam Lee',
        'email' => 'sam@company.com',
        'company' => 'Acme',
        'service' => 'Marketing automation',
        'details' => 'We need automated follow-up for new leads.',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        config(['portfolio.contact.email' => 'gayle@example.com']);
    }

    public function test_valid_inquiry_is_emailed(): void
    {
        $this->postJson('/inquiries', $this->inquiry)->assertOk();

        Mail::assertSent(InquiryReceived::class, fn ($mail) => $mail->hasTo('gayle@example.com')
            && $mail->hasReplyTo('sam@company.com')
            && $mail->inquiry['service'] === 'Marketing automation');
    }

    public function test_form_post_without_javascript_redirects_back_to_contact(): void
    {
        $this->post('/inquiries', $this->inquiry)
            ->assertRedirect(route('home').'#contact')
            ->assertSessionHas('inquiry_sent');
    }

    public function test_invalid_inquiry_returns_field_errors(): void
    {
        $this->postJson('/inquiries', ['email' => 'not-an-email', 'service' => 'Something else'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'service', 'details']);

        Mail::assertNothingSent();
    }

    public function test_inquiry_is_refused_when_no_recipient_is_configured(): void
    {
        config(['portfolio.contact.email' => null]);

        $this->postJson('/inquiries', $this->inquiry)->assertServiceUnavailable();

        Mail::assertNothingSent();
    }
}
