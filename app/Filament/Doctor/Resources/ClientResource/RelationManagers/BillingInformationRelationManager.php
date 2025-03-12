<?php

namespace App\Filament\Doctor\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class BillingInformationRelationManager extends RelationManager
{
    protected static string $relationship = 'billingInformation';

    protected static ?string $recordTitleAttribute = 'rfc';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rfc')
                    ->label('RFC')
                    ->required()
                    ->unique('billing_information', 'rfc', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('razon_social')
                    ->label('Razón Social')
                    ->required()
                    ->unique('billing_information', 'razon_social', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('regimen_fiscal')
                    ->label('Régimen Fiscal')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    PhoneInput::make('phone'),
                TextInput::make('street_address')
                    ->label('Street Address')
                    ->maxLength(255),
                TextInput::make('numero_interior')
                    ->label('Interior Number')
                    ->maxLength(255),
                TextInput::make('numero_exterior')
                    ->label('Exterior Number')
                    ->maxLength(255),
                TextInput::make('colonia')
                    ->label('Colonia')
                    ->maxLength(255),
                TextInput::make('municipio')
                    ->label('Municipality')
                    ->maxLength(255),
                TextInput::make('state')
                    ->label('State')
                    ->maxLength(255),
                TextInput::make('country')
                    ->label('Country')
                    ->maxLength(255),
                TextInput::make('postal_code')
                    ->label('Postal Code')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rfc')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('razon_social')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}