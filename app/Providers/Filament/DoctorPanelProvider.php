<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use App\Models\Clinic;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\OrderMoneyChart;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\AppointmentsChart;
use App\Http\Middleware\AssignGlobalScopes;
use App\Filament\Doctor\Pages\DoctorCalendar;
use App\Filament\Doctor\Widgets\StatsOverview;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use App\Filament\Doctor\Widgets\AppointmentsCalendarWidget;
use App\Filament\Widgets\TotalCustomersChart;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Filament\Enums\ThemeMode;
use App\Filament\Admin\Themes\PetClinic;

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        //$tenant = Filament::getTenant();
        //$user = Filament::auth()->user();

        $tenant = $this->getTenantId(url()->current());

        return $panel
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
            ->id('doctor')
            ->path('doctor')
            ->font('Poppins')
            ->maxContentWidth(MaxWidth::Full)
            //->simplePageMaxContentWidth(MaxWidth::Full)
            ->spa()
            ->spaUrlExceptions(fn(): array => [
                '*/doctor/' . $tenant . '/doctor-calendar'
            ])
            ->login()
            ->profile(EditProfile::class)
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->tenant(Clinic::class)
            ->tenantMiddleware([
                ApplyTenantScopes::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ], isPersistent: true)
            ->discoverResources(in: app_path('Filament/Doctor/Resources'), for: 'App\\Filament\\Doctor\\Resources')
            ->discoverPages(in: app_path('Filament/Doctor/Pages'), for: 'App\\Filament\\Doctor\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            //->discoverWidgets(in: app_path('Filament/Doctor/Widgets'), for: 'App\\Filament\\Doctor\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                AppointmentsChart::class,
                OrderMoneyChart::class,
                TotalCustomersChart::class,
                AppointmentsCalendarWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
                AssignGlobalScopes::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseTransactions()
            ->unsavedChangesAlerts()
            ->tenantBillingProvider(new BillingProvider('default'))
            ->requiresTenantSubscription()
            ->theme(asset('css/filament/admin/theme.css'))
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
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
                //->schedulerLicenseKey()
            ]);
    }

    function getTenantId($url)
    {
        // Get the path from the URL (e.g., "/doctor/2/doctor-calendar")
        $path = parse_url($url, PHP_URL_PATH);

        // Use regex to match "/doctor/<number>/doctor-calendar" and capture the number
        if (preg_match('/\/doctor\/(\d+)\/doctor-calendar/', $path, $matches)) {
            return $matches[1]; // The tenant ID is captured in the first group
        }

        return null; // Return null if the pattern isn't found
    }

}
