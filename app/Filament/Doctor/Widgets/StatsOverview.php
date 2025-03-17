<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '120s';
    //protected ?string $heading = 'Analytics';
    //protected ?string $description = 'An overview of some analytics.';

    protected function getStats(): array
    {
        $order = Order::with('orderProducts')->get();
        $ordersTotal = $order->sum('order_total');

        return [
            Stat::make('Orders Total', number_format($ordersTotal, 2, ',', '.') . ' ' . config('money.defaults.currency'))
                //->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Appointments', Filament::getTenant()->activeAppointments->count())
                //->description('7% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make("Today's Appointments", Filament::getTenant()->todaysAppointments->count())
                //->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
