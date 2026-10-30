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

    public function test_each_service_links_to_its_own_pipeline_stage(): void
    {
        $response = $this->get('/');
        $stageNames = array_column(config('portfolio.stages'), 'name');

        foreach (config('portfolio.services') as $service) {
            $number = array_search($service['stage'], $stageNames) + 1;

            $response->assertSee('href="#stage-'.$number.'">Where it fits: '.$service['stage'], false);
        }
    }

    public function test_every_in_page_link_has_a_target(): void
    {
        $html = $this->get('/')->getContent();
        preg_match_all('/id="([^"]+)"/', $html, $ids);
        preg_match_all('/href="#([^"]+)"/', $html, $anchors);

        $this->assertSame([], array_values(array_diff(array_unique($anchors[1]), $ids[1])));
    }

    public function test_intro_phrases_link_to_their_services(): void
    {
        $this->get('/')
            ->assertSee('href="#service-creative-research-production">research-led ad creative</a>', false)
            ->assertSee('href="#service-paid-advertising-lead-generation">well-structured Meta campaigns</a>', false)
            ->assertSee('href="#service-funnel-crm-development">the funnels, CRM, and automations</a>', false);
    }

    public function test_link_preview_image_and_icons_exist(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['og:image', 'twitter:image'] as $tag) {
            $this->assertMatchesRegularExpression('/(property|name)="'.$tag.'" content="https?:\/\/[^"]+og-image\.jpg"/', $html);
        }
        foreach (['images/og-image.jpg', 'favicon.ico', 'icon-512.png', 'apple-touch-icon.png'] as $file) {
            $this->assertFileExists(public_path($file));
            $this->assertGreaterThan(0, filesize(public_path($file)));
        }
    }

    public function test_turnstile_widget_renders_only_when_a_site_key_is_set(): void
    {
        config(['services.turnstile.site_key' => null]);
        $this->get('/')->assertDontSee('cf-turnstile', false);

        config(['services.turnstile.site_key' => 'site-key-123']);
        $this->get('/')
            ->assertSee('data-sitekey="site-key-123"', false)
            ->assertSee('challenges.cloudflare.com/turnstile/v0/api.js', false);
    }

    public function test_unset_contact_details_show_as_placeholders(): void
    {
        config(['portfolio.contact.email' => null]);

        $this->get('/')
            ->assertSee('<span class="ph">[email address]</span>', false);
    }

    public function test_configured_contact_details_replace_placeholders(): void
    {
        config(['portfolio.contact.email' => 'gayle@example.com']);

        $this->get('/')
            ->assertSee('<a href="mailto:gayle@example.com">gayle@example.com</a>', false)
            ->assertDontSee('[email address]');
    }
}
