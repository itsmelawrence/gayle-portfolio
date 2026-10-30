<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JarvisLeadFeedTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'test-leads-token';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.jarvis.leads_token' => self::TOKEN]);
    }

    public function test_feed_does_not_exist_without_a_configured_token(): void
    {
        config(['services.jarvis.leads_token' => null]);

        $this->withToken(self::TOKEN)->getJson('/api/jarvis/leads')->assertNotFound();
    }

    public function test_feed_rejects_a_missing_or_wrong_token(): void
    {
        $this->getJson('/api/jarvis/leads')->assertUnauthorized();
        $this->withToken('wrong')->getJson('/api/jarvis/leads')->assertUnauthorized();
    }

    public function test_feed_returns_leads_after_the_cursor_without_visitor_details(): void
    {
        $first = $this->lead(['name' => 'First', 'utm_source' => 'linkedin']);
        $second = $this->lead(['name' => 'Second']);

        $response = $this->withToken(self::TOKEN)->getJson('/api/jarvis/leads')
            ->assertOk()
            ->assertJsonPath('data.0.id', $first->id)
            ->assertJsonPath('data.0.message', 'We need automated follow-up.')
            ->assertJsonPath('data.0.utm_source', 'linkedin')
            ->assertJsonPath('data.0.status', 'new')
            ->assertJsonPath('has_more', false);
        $this->assertArrayNotHasKey('ip_address', $response->json('data.0'));
        $this->assertArrayNotHasKey('user_agent', $response->json('data.0'));

        $this->withToken(self::TOKEN)->getJson('/api/jarvis/leads?after_id='.$first->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $second->id);
    }

    public function test_feed_pages_in_hundreds(): void
    {
        foreach (range(1, 101) as $number) {
            $this->lead(['email' => "lead$number@example.com"]);
        }

        $this->withToken(self::TOKEN)->getJson('/api/jarvis/leads')
            ->assertOk()
            ->assertJsonCount(100, 'data')
            ->assertJsonPath('has_more', true);
    }

    private function lead(array $attributes = []): Lead
    {
        return Lead::create([
            'name' => 'Sam Lee',
            'email' => 'sam@company.com',
            'service' => 'Marketing automation',
            'details' => 'We need automated follow-up.',
            'ip_address' => '203.0.113.5',
            'user_agent' => 'Test browser',
            ...$attributes,
        ]);
    }
}
