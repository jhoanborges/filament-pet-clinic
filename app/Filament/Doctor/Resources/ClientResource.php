<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Doctor\Resources\ClientResource\Pages;
use App\Filament\Doctor\Resources\ClientResource\RelationManagers\BillingInformationRelationManager;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Facades\Filament;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Icon for the sidebar

/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->clients->count();
    }
*/
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'lastname', 'pet.name', 'phone'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->label(__('Birthday')),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('lastname')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('birthday')->date(),
                Tables\Columns\BooleanColumn::make('allow_email_notification')
                    ->label('Email Notifications'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BillingInformationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
