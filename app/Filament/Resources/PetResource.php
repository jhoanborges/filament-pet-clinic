<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Pet;
use Filament\Forms;
use Filament\Tables;
use App\Enums\PetType;
use App\Models\Clinic;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PetResource\Pages;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make([
                /*Forms\Components\FileUpload::make('avatar')
                        ->image()
                        ->imageEditor(),*/
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\DatePicker::make('date_of_birth')->native(false)->required()->closeOnDateSelection()->displayFormat('M d Y'),
                Forms\Components\Select::make('type')->native(false)->options(PetType::class),
                Forms\Components\Select::make('clinic_id')->relationship('clinics', 'name')->multiple()->preload()->searchable(),

                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('lastname')->maxLength(255),
                        Forms\Components\TextInput::make('ocupacion')->label('Occupation')->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->unique(\App\Models\Client::class, 'email')->maxLength(255),
                        PhoneInput::make('phone')->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('birthday')->label('Birthday'),
                        Forms\Components\TextInput::make('street_address')->maxLength(255),
                        Forms\Components\TextInput::make('colony')->label('Colony/Neighborhood')->maxLength(255),
                        Forms\Components\TextInput::make('city')->maxLength(255),
                        Forms\Components\TextInput::make('municipality')->maxLength(255),
                        Forms\Components\TextInput::make('postal_code')->label('Postal Code')->maxLength(255),
                        Forms\Components\Toggle::make('allow_email_notification')->label('Allow Email Notifications')->default(false),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action->modalHeading('Create Client')->modalSubmitActionLabel('Add Client');
                    })
                    ->createOptionUsing(function (array $data): int {
                        $client = \App\Models\Client::create($data);
                        return $client->id;
                    }),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*Tables\Columns\ImageColumn::make('avatar')
                 ->circular(),*/
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('clinics.name')->sortable()->searchable()->badge(),
                // Replaced date_of_birth with age calculation
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->getStateUsing(function (Pet $record): string {
                        if (!$record->date_of_birth) {
                            return 'N/A';
                        }
                        $age = Carbon::parse($record->date_of_birth)->age;
                        return $age . ' year' . ($age !== 1 ? 's' : '');
                    })
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query->orderBy('date_of_birth', $direction === 'asc' ? 'desc' : 'asc');
                        },
                    ),
                Tables\Columns\TextColumn::make('client.name')->sortable()->searchable(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('clinic_id')->label('Clinic')->relationship('clinics', 'name')->multiple()->preload()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function (Pet $record) {
                    // Delete file
                    Storage::delete('public/' . $record->avatar);
                }),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateActions([Tables\Actions\CreateAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
