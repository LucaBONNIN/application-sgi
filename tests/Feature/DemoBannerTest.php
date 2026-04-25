<?php

namespace Tests\Feature;

use Tests\TestCase;

class DemoBannerTest extends TestCase
{
    public function test_demo_banner_view_renders_reset_message_and_countdown(): void
    {
        $html = view('filament.demo-banner')->render();

        $this->assertStringContainsString('demo instance', strtolower($html));
        $this->assertStringContainsString('Next reset in', $html);
        $this->assertStringContainsString('calculateSeconds', $html);
        $this->assertStringContainsString('x-data', $html);
    }
}
