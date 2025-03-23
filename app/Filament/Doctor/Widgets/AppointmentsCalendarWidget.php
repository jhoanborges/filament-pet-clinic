<?php

namespace App\Filament\Doctor\Widgets;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Appointment;
//use App\Filament\Resources\EventResource;
use Filament\Facades\Filament;
use App\Enums\AppointmentStatus;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use App\Filament\Doctor\Resources\AppointmentResource\Pages;
use App\Models\User;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;

class AppointmentsCalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Appointment::class;

    //protected static string $view = 'filament.doctor.widgets.appointments-calendar-widget';

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay,dayGridMonth',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }


    protected function headerActions(): array
 {
     return [
         CreateAction::make()
             ->mutateFormDataUsing(function (array $data): array {
                $tenant = Filament::getTenant();
                $user = User::with('clinics')->find($tenant->id);
                //for now user can only have 1 clinic

                 return [
                     ...$data,
                     'clinic_id' => $user->clinics->first()->id,
                     'doctor_id' => $user->id
                 ];
             })
     ];
 }

    public function getFormSchema(): array
    {
        return [
            Select::make('pet_id')
                ->label('Pet')
                ->relationship(name: 'pet', titleAttribute: 'name')
                ->allowHtml()
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),

            DatePicker::make('date')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->closeOnDateSelection()
                ->required(),

            Grid::make()
                ->schema([
                    TimePickerField::make('start_time')->label('Start Time')->okLabel("Confirm")->cancelLabel("Cancel"),
                    TimePickerField::make('end_time')->label('End Time')->okLabel("Confirm")->cancelLabel("Cancel"),
                ]),
            TextInput::make('description'),
            Select::make('status')
                ->native(false)
                ->options(AppointmentStatus::class)
        ];
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {

        // You can use $fetchInfo to filter events by date.
        // This method should return an array of event-like objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#returning-events
        // You can also return an array of EventData objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#the-eventdata-class

        $appointments = Appointment::
            with('pet')
            ->get()
            ->map(
                fn(Appointment $appointment) => [
                    'id' => $appointment->id,
                    'title' => $appointment->pet?->name ?? '',
                    // Use the helper methods to handle the logic
                    'start' => $this->formatToIso8601(
                        $this->combineDateAndTime($appointment->date, $appointment->start_time)
                    ),
                    'end' => $this->formatToIso8601(
                        $this->combineDateAndTime($appointment->date, $appointment->end_time)
                    ),
                    //'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'description' => $appointment->description ?? '',
                    'status' => $appointment->status ?? '',
                ]
            )
            ->all();

        return $appointments;
    }

    /**
     * Combine the date and time and return a Carbon instance.
     */
    public function combineDateAndTime($date, $time)
    {
        $parsedDate = Carbon::parse($date)->toDateString(); // Ensure we get the date in YYYY-MM-DD format
        $parsedTime = Carbon::parse($time)->format('H:i:s'); // Ensure the time is in H:i:s format

        return Carbon::parse($parsedDate . ' ' . $parsedTime);
    }

    /**
     * Convert a Carbon instance to ISO 8601 format.
     */
    public function formatToIso8601(Carbon $carbonInstance)
    {
        return $carbonInstance->toIso8601String();
    }


    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){

            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event?.extendedProps?.description+"' }");
        }
    JS;
    }
//Editing event after drag and drop
//You can fill the form with the event's new data by using the mountUsing method on the EditAction.
    protected function modalActions(): array
 {
     return [
         EditAction::make()
             ->mountUsing(
                 function (Appointment $record, Forms\Form $form, array $arguments) {

                    $parsedStartTime = Carbon::parse($record->start_time)->format('H:i:s'); // Ensure the time is in H:i:s format
                    $parsedEndTime = Carbon::parse($record->end_time)->format('H:i:s'); // Ensure the time is in H:i:s format

                     $form->fill([
                        'date' => isset($arguments['event']['start']) ? Carbon::parse($arguments['event']['start'])->toDateString() : $record->date,
                         'status' => $record->status,
                         'start_time' => $parsedStartTime,
                         'end_time' =>  $parsedEndTime,
                         'description' =>  $record->description,
                     ]);
                 }
             ),
         DeleteAction::make(),
     ];
 }

}
