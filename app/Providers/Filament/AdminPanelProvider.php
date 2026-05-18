<?php

namespace App\Providers\Filament;

use App\Http\Middleware\FilamentAdminAuthenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Pineus Tilu Admin')
            ->brandLogo(asset('images/dashboard/logo.png'))
            ->darkModeBrandLogo(asset('images/dashboard/logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/dashboard/logo.png'))
            // No ->login() - use existing login page
            ->colors([
                'primary' => [
                    50 => '#f0fdf4',
                    100 => '#dcfce7',
                    200 => '#bbf7d0',
                    300 => '#86efac',
                    400 => '#4ade80',
                    500 => '#22c55e',
                    600 => '#017249',  // Brand primary color
                    700 => '#0b5a3e',  // Brand text color
                    800 => '#166534',
                    900 => '#14532d',
                    950 => '#052e16',
                ],
                'gray' => Color::Zinc,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Kembali ke Website')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left-circle'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\BookingStatsWidget::class,
                \App\Filament\Widgets\BookingChart::class,
                \App\Filament\Widgets\LatestBookingsWidget::class,
                \App\Filament\Widgets\GalleryByTypeChart::class,
                \App\Filament\Widgets\ItemByTypeChart::class,
            ])
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
            ->authMiddleware([
                FilamentAdminAuthenticate::class,
            ]);
    }
}
