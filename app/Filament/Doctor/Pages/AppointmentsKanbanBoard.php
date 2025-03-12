<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Illuminate\Support\Collection;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class AppointmentsKanbanBoard extends KanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static string $model = Appointment::class;
    protected static string $statusEnum = AppointmentStatus::class;
    protected static string $recordTitleAttribute = 'status';
    protected static ?string $title = 'Appointments Kanban';
    protected static string $recordStatusAttribute = 'status';
    public bool $disableEditModal = false;

    protected static string $view = 'filament-kanban::kanban-board';
    protected static string $headerView = 'filament-kanban::kanban-header';
    protected static string $recordView = 'filament-kanban::kanban-record';
    protected static string $statusView = 'filament-kanban::kanban-status';
    protected static string $scriptsView = 'filament-kanban::kanban-scripts';

    protected function records(): Collection
    {
        return Appointment::all();
    }
/*
    protected function getEditModalFormSchema(null|int $recordId): array
{
    return [
        TimePicker::make('start_time')
        ->hoursStep(1)
        ->minutesStep(30),
        TimePicker::make('end_time')
        ->hoursStep(1)
        ->minutesStep(30),
    ];
}
*/

}
