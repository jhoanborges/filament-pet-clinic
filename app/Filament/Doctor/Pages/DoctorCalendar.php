<?php

namespace App\Filament\Doctor\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use App\Filament\Doctor\Widgets\AppointmentsCalendarWidget;

class DoctorCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $title = 'Calendar';
    protected static string $view = 'filament.doctor.pages.doctor-calendar';
    protected static ?string $navigationGroup = 'Citas';

    protected function getHeaderWidgets(): array
    {
        return [
            AppointmentsCalendarWidget::class
        ];
    }



    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenTwoExtraLarge;
    }
}
