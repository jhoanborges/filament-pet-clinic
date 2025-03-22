<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Admin\Themes\PetClinic;
use App\Filament\Widgets\OrderMoneyChart;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\AppointmentsChart;
use App\Filament\Widgets\TotalCustomersChart;
use App\Filament\Doctor\Widgets\StatsOverview;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use TomatoPHP\FilamentTenancy\FilamentTenancyAppPlugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use App\Filament\Doctor\Widgets\AppointmentsCalendarWidget;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->font('Poppins')
            ->maxContentWidth(MaxWidth::Full)
            ->spa()
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('images/logo.svg'))
            ->darkModeBrandLogo(asset('images/logo-white.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon/favicon.ico'))
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => '#9056a3',
                'secondary' => '#c978b2',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'pink' => Color::hex('#c978b2'),
                'purple' => Color::hex('#9056a3'),
                'dark-blue' => Color::hex('#3a419a'),
                'light-blue' => Color::hex('#e3f3f7'),
                'dark-gray' => Color::hex('#333'),
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->discoverResources(in: app_path('Filament/Doctor/Resources'), for: 'App\\Filament\\Doctor\\Resources')
            ->discoverPages(in: app_path('Filament/Doctor/Pages'), for: 'App\\Filament\\Doctor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                AppointmentsChart::class,
                OrderMoneyChart::class,
                TotalCustomersChart::class,
                AppointmentsCalendarWidget::class,
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
            ->databaseTransactions()
            ->unsavedChangesAlerts()
            ->theme(asset('css/filament/admin/theme.css'))
            //->tenant(Team::class)
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ], isPersistent: true)
            ->plugins([
                FilamentTenancyAppPlugin::make(),
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable(true)
                    ->locale('es')
                    ->timezone(config('app.timezone'))
                    ->plugins(['dayGrid', 'timeGrid'])
                    ->config([]),
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->registerTheme([PetClinic::getName() => PetClinic::class]),
                FilamentApexChartsPlugin::make()
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
