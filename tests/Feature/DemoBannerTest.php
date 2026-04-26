<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DemoBannerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_demo_banner_view_renders_reset_message_and_countdown(): void
    {
        $html = view('filament.demo-banner')->render();

        $this->assertStringContainsString(__('filament/demo.banner.title'), $html);
        $this->assertStringContainsString(__('filament/demo.banner.message'), $html);
        $this->assertStringContainsString(__('filament/demo.banner.next_reset'), $html);
        $this->assertStringContainsString(__('filament/demo.banner.resetting'), $html);
        $this->assertStringContainsString('calculateSeconds', $html);
        $this->assertStringContainsString('waitForReset', $html);
        $this->assertStringContainsString('x-data', $html);
    }

    public function test_demo_credentials_view_shows_admin_and_user_credentials(): void
    {
        $user = User::factory()->create();

        $html = view('filament.demo-credentials', ['firstUser' => $user])->render();

        $this->assertStringContainsString('admin.intendance.1@127011.xyz', $html);
        $this->assertStringContainsString($user->email, $html);
        $this->assertStringContainsString('password', $html);
    }

    public function test_demo_credentials_view_shows_only_admin_when_no_user_exists(): void
    {
        $html = view('filament.demo-credentials', ['firstUser' => null])->render();

        $this->assertStringContainsString('admin.intendance.1@127011.xyz', $html);
        $this->assertStringNotContainsString('@example', $html);
    }
}
