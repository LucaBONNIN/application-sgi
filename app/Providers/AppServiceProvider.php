<?php

namespace App\Providers;

use App\Models\User;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @see https://github.com/TappNetwork/filament-auditing/issues/67
        Gate::define('audit', function ($user, $resource = null) {
            return true;
        });

        Gate::define('restoreAudit', function ($user, $resource = null) {
            return true;
        });

        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        if ($this->app->isProduction()) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_START,
                fn () => view('filament.demo-banner'),
            );

            FilamentView::registerRenderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn () => view('filament.demo-credentials', [
                    'firstUser' => User::where('email', '!=', 'admin.intendance.1@127011.xyz')
                        ->orderBy('id')
                        ->first(),
                ]),
            );
        }
    }
}
