<?php

namespace App\Filament\Doctor\Resources;

use App\Enums\AppointmentStatus;
use App\Filament\Doctor\Resources\AppointmentResource\Pages;
use App\Filament\Doctor\Resources\AppointmentResource\RelationManagers\NotesRelationManager;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Role;
use App\Models\Slot;
use App\Support\AvatarOptions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationGroup = 'Citas';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 1;
/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->activeAppointments->count();
    }
*/

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('pet_id')
                ->label(__('Pet'))
                ->relationship(name: 'pet', titleAttribute: 'name')
                ->allowHtml()
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),
            Forms\Components\DatePicker::make('date')
                ->label(__('Date'))
                ->native(false)
                ->displayFormat('M d, Y')
                ->closeOnDateSelection()
                ->required()
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set('slot_id', null)),
            TimePickerField::make('start_time')
                ->label(__('Start Time'))
                ->okLabel(__("Confirm"))
                ->cancelLabel(__("Cancel")),
            TimePickerField::make('end_time')
                ->label(__('End Time'))
                ->okLabel(__("Confirm"))
                ->cancelLabel(__("Cancel")),
            Forms\Components\TextInput::make('description')
                ->label(__('Description'))
                ->required(),
            Forms\Components\Select::make('status')
                ->label(__('Status'))
                ->native(false)
                ->options(AppointmentStatus::class)
                ->visibleOn(Pages\EditAppointment::class)
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('pet.avatar')
                ->label(__('Image'))
                ->circular(),
            Tables\Columns\TextColumn::make('pet.name')
                ->label(__('Pet Name'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('description')
                ->label(__('Description'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('date')
                ->label(__('Date'))
                ->date('M d, Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('slot.formatted_time')
                ->label(__('Time'))
                ->badge()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label(__('Status'))
                ->badge()
                ->sortable()
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\Action::make('Atendida')
                ->label(__('Attended'))
                ->action(function (Appointment $record) {
                    $record->status = AppointmentStatus::Attended;
                    $record->save();
                })
                ->visible(fn (Appointment $record) => $record->status == AppointmentStatus::Created)
                ->color('success')
                ->icon('heroicon-o-check'),
            Tables\Actions\Action::make('Cancel')
                ->label(__('Cancel'))
                ->action(function (Appointment $record) {
                    $record->status = AppointmentStatus::Canceled;
                    $record->save();
                })
                ->visible(fn (Appointment $record) => $record->status != AppointmentStatus::Canceled)
                ->color('danger')
                ->icon('heroicon-o-x-mark'),
            Tables\Actions\EditAction::make()
                ->label(__('Edit')),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('Delete')),
            ]),
        ])
        ->emptyStateActions([
            Tables\Actions\CreateAction::make()
                ->label(__('Create')),
        ]);
}
    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
/*
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::new()->count();
    }
    */

}
