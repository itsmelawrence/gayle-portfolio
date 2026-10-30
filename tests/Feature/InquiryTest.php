<?php

namespace Tests\Feature;

use App\Mail\InquiryReceived;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

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
        config([
            'portfolio.contact.email' => 'gayle@example.com',
            'services.turnstile.secret_key' => null,
        ]);
    }

    public function test_valid_inquiry_is_stored_and_emailed(): void
    {
        $this->postJson('/inquiries', [...$this->inquiry, 'utm_source' => 'linkedin', 'referrer' => 'https://www.linkedin.com/'])
            ->assertOk();

        $lead = Lead::sole();
        $this->assertSame('sam@company.com', $lead->email);
        $this->assertSame('new', $lead->status);
        $this->assertSame('linkedin', $lead->utm_source);
        $this->assertSame('https://www.linkedin.com/', $lead->referrer);
        $this->assertNotNull($lead->ip_address);
        $this->assertNotNull($lead->notified_at);

        Mail::assertSent(InquiryReceived::class, fn ($mail) => $mail->hasTo('gayle@example.com')
            && $mail->hasReplyTo('sam@company.com')
            && $mail->lead->service === 'Marketing automation');
    }

    public function test_form_post_without_javascript_redirects_back_to_contact(): void
    {
        $this->post('/inquiries', $this->inquiry)
            ->assertRedirect(route('home').'#contact')
            ->assertSessionHas('inquiry_sent');

        $this->assertDatabaseCount('leads', 1);
    }

    public function test_invalid_inquiry_returns_field_errors(): void
    {
        $this->postJson('/inquiries', ['email' => 'not-an-email', 'service' => 'Something else'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'service', 'details']);

        $this->assertDatabaseCount('leads', 0);
        Mail::assertNothingSent();
    }

    public function test_lead_is_stored_even_without_a_notification_address(): void
    {
        config(['portfolio.contact.email' => null]);

        $this->postJson('/inquiries', $this->inquiry)->assertOk();

        $this->assertNull(Lead::sole()->notified_at);
        Mail::assertNothingSent();
    }

    public function test_lead_survives_a_mail_failure(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

        $this->postJson('/inquiries', $this->inquiry)->assertOk();

        $this->assertNull(Lead::sole()->notified_at);
    }

    public function test_honeypot_submissions_look_successful_but_are_dropped(): void
    {
        $this->postJson('/inquiries', [...$this->inquiry, 'website' => 'https://spam.example'])->assertOk();

        $this->assertDatabaseCount('leads', 0);
        Mail::assertNothingSent();
    }

    public function test_turnstile_token_is_required_and_verified_when_configured(): void
    {
        config(['services.turnstile.secret_key' => 'secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::sequence()
            ->push(['success' => false])
            ->push(['success' => true])]);

        $this->postJson('/inquiries', $this->inquiry)
            ->assertJsonValidationErrors(['cf-turnstile-response' => 'Please complete the verification check.']);

        $this->postJson('/inquiries', [...$this->inquiry, 'cf-turnstile-response' => 'bad-token'])
            ->assertJsonValidationErrors(['cf-turnstile-response' => 'The verification check failed. Please try again.']);

        $this->postJson('/inquiries', [...$this->inquiry, 'cf-turnstile-response' => 'good-token'])->assertOk();

        $this->assertDatabaseCount('leads', 1);
        Http::assertSent(fn ($request) => $request['secret'] === 'secret' && $request['response'] === 'good-token');
    }

    public function test_notification_email_shows_every_submitted_field_safely(): void
    {
        $lead = Lead::create([...$this->inquiry, 'details' => "Line one\n<script>alert(1)</script>", 'utm_source' => 'linkedin', 'landing_page' => '/?utm_source=linkedin']);
        $mail = new \App\Mail\InquiryReceived($lead);

        $mail->assertHasSubject('New inquiry from Sam Lee · Marketing automation');
        $mail->assertSeeInHtml('Sam Lee');
        $mail->assertSeeInHtml('mailto:sam@company.com', false);
        $mail->assertSeeInHtml('Acme');
        $mail->assertSeeInHtml('Marketing automation');
        $mail->assertSeeInHtml('Line one<br />', false);
        $mail->assertDontSeeInHtml('<script>alert(1)</script>', false);
        $mail->assertSeeInHtml('linkedin');
        $mail->assertSeeInHtml('Lead #'.$lead->id);
        $mail->assertSeeInText('Company:  Acme');
        $mail->assertSeeInText('Project details');
    }
}
