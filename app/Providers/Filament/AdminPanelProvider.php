<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            // -----------------------------------------------------------------
            // BASIC PANEL CONFIGURATION
            // -----------------------------------------------------------------
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // -----------------------------------------------------------------
            // BRANDING
            // -----------------------------------------------------------------
            ->brandName('Satu Ayat Sehari')
            ->brandLogo(fn () => view('filament.logo'))
            ->brandLogoHeight('40px')
            ->sidebarCollapsibleOnDesktop()

            // -----------------------------------------------------------------
            // SECURITY
            // -----------------------------------------------------------------
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->databaseNotifications(false)

            // -----------------------------------------------------------------
            // THEME COLORS
            // -----------------------------------------------------------------
            ->colors([
                'primary' => Color::Amber,
            ])

            // -----------------------------------------------------------------
            // AUTO DISCOVERY
            // -----------------------------------------------------------------
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            // -----------------------------------------------------------------
            // REGISTERED PAGES
            // -----------------------------------------------------------------
            ->pages([
                Pages\Dashboard::class,
            ])

            // -----------------------------------------------------------------
            // DASHBOARD WIDGETS
            // -----------------------------------------------------------------
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])

            // -----------------------------------------------------------------
            // PANEL MIDDLEWARE
            // -----------------------------------------------------------------
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            // -----------------------------------------------------------------
            // AUTHENTICATION MIDDLEWARE
            // -----------------------------------------------------------------
            ->authMiddleware([
                Authenticate::class,
            ])

            // -----------------------------------------------------------------
            // CUSTOM FOOTER
            // -----------------------------------------------------------------
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => view('filament.footer')->render()
            );
    }
}