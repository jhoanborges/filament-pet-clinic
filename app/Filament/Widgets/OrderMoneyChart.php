<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\OrderProduct;
use App\Helpers\ThemeHelper;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderMoneyChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'orderMoneyChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Money generated per month';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
  
        $data = OrderProduct::whereYear('created_at', now()->year)
        ->get()
        ->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->month;
        })
        ->map(function ($group) {
            return $group->sum('price');
        })
        ->toArray();
    
        $monthlyPrices = [];

        for ($i = 1; $i <= 12; $i++) {
            $amount = $data[$i] ?? 0; 
            $monthlyPrices[] = number_format($amount, 2, ',', '.');
        }   

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Total Generated',
                    'data' => $monthlyPrices,
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => [ThemeHelper::getPrimaryColor()],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => true,
                ],
            ],
        ];
    }
}
