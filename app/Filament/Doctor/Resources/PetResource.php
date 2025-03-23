<?php

namespace App\Filament\Doctor\Resources;

use App\Models\Pet;
use Filament\Forms;
use Filament\Tables;
use App\Enums\PetType;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Storage;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use App\Filament\Doctor\Resources\PetResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'phosphor-dog';

    protected static ?int $navigationSort = 3;

    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->pets->count();
    }*/


    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make(__('Pet Details'))
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make(__('Information'))
                            ->schema([
                                Forms\Components\FileUpload::make('avatar')
                                    ->avatar()
                                    ->image()
                                    ->imageEditor()
                                    ->label(__('Avatar')),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label(__('Name')),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->native(false)
                                    ->required()
                                    ->closeOnDateSelection()
                                    ->displayFormat('M d Y')
                                    ->label(__('Date of Birth')),
                                Forms\Components\Select::make('type')
                                    ->native(false)
                                    ->options(PetType::class)
                                    ->label(__('Type')),
                                Forms\Components\Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->native(false)
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Name'))
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('lastname')
                                            ->label(__('Lastname'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('ocupacion')
                                            ->label(__('Occupation'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->label(__('Email'))
                                            ->email()
                                            ->required()
                                            ->unique(Client::class, 'email', ignoreRecord: true)
                                            ->maxLength(255),
                                        PhoneInput::make('phone')
                                            ->label(__('Phone')),
                                        Forms\Components\Select::make('gender')
                                            ->label(__('Gender'))
                                            ->options([
                                                'male' => __('Male'),
                                                'female' => __('Female'),
                                                'other' => __('Other'),
                                            ])
                                            ->required(),
                                        Forms\Components\DatePicker::make('birthday')
                                            ->label(__('Birthday'))
                                            ->native(false)
                                            ->closeOnDateSelection()
                                            ->displayFormat('M d Y'),
                                        Forms\Components\TextInput::make('street_address')
                                            ->label(__('Street Address'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('colony')
                                            ->label(__('Colony/Neighborhood'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('city')
                                            ->label(__('City'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('municipality')
                                            ->label(__('Municipality'))
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('postal_code')
                                            ->label(__('Postal Code'))
                                            ->maxLength(255),
                                        Forms\Components\Toggle::make('allow_email_notification')
                                            ->label(__('Allow Email Notifications'))
                                            ->default(false),
                                    ]),
                            ]),
                        Tabs\Tab::make(__('Files'))
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->openable()
                                    ->panelLayout('grid')
                                    ->downloadable()
                                    ->previewable()
                                    ->multiple()
                                    ->reorderable()
                                    ->disk('r2')
                                    ->collection('pets')
                                    ->maxFiles(100),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date('M d Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->sortable()
                    ->searchable()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Pet $record) {
                        // Delete file
                        Storage::delete('public/' . $record->avatar);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
