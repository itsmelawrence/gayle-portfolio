<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    public function test_home_page_renders_every_project(): void
    {
        $response = $this->get('/');

        $response->assertOk()->assertSee('Ads, creative, and the systems behind them.');

        foreach (config('portfolio.projects') as $project) {
            $response->assertSee('id="'.$project['slug'].'"', false);
        }
    }

    public function test_unset_contact_details_show_as_placeholders(): void
    {
        config(['portfolio.contact.email' => null]);

        $this->get('/')
            ->assertSee('<span class="ph">[email address]</span>', false)
            ->assertSee('data-connected="false"', false);
    }

    public function test_configured_contact_details_replace_placeholders(): void
    {
        config(['portfolio.contact.email' => 'gayle@example.com']);

        $this->get('/')
            ->assertSee('<a href="mailto:gayle@example.com">gayle@example.com</a>', false)
            ->assertDontSee('[email address]')
            ->assertSee('data-connected="true"', false);
    }
}
