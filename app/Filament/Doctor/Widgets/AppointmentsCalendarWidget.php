<?php

namespace App\Filament\Doctor\Widgets;

use Carbon\Carbon;
use App\Models\Appointment;
use Filament\Widgets\Widget;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use \Guava\Calendar\Widgets\CalendarWidget;

class AppointmentsCalendarWidget extends CalendarWidget
{
    //protected static string $view = 'filament.doctor.widgets.appointments-calendar-widget';
    protected string $calendarView = 'dayGridMonth';

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        $appointments = Filament::getTenant()->appointments;
        $appointments = Appointment::whereIn('id', $appointments->pluck('id'))
        ->with('pet')
        ->get();

        ///dd($appointments);

        $events = $appointments->map(function (Appointment $appointment): array {
            // Parse the date
            $date = Carbon::parse($appointment->date)->toDateString();
            
            // Extract time from start_time and end_time
            $startTimeStr = Carbon::parse($appointment->start_time)->format('H:i:s');
            $endTimeStr = Carbon::parse($appointment->end_time)->format('H:i:s');
            
            // Create correct date-time objects
            $startDateTime = Carbon::parse($date . ' ' . $startTimeStr);
            $endDateTime = Carbon::parse($date . ' ' . $endTimeStr);
            
            return [
                'title' => $appointment->pet?->name,
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
            ];
        })->values()->all();
    
        return $events;
    }
}
