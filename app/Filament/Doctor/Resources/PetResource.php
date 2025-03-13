<?php

namespace App\Filament\Doctor\Resources;

use App\Models\Pet;
use Filament\Forms;
use Filament\Tables;
use App\Enums\PetType;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use App\Filament\Doctor\Resources\PetResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?int $navigationSort = 3;

    protected static ?string $tenantOwnershipRelationshipName = 'clinics';

    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->pets->count();
    }


    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Pet information')
                            ->schema([

                                Forms\Components\FileUpload::make('avatar')
                                    ->image()
                                    ->imageEditor(),
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->native(false)
                                    ->required()
                                    ->closeOnDateSelection()
                                    ->displayFormat('M d Y'),
                                Forms\Components\Select::make('type')
                                    ->native(false)
                                    ->options(PetType::class),
                                Forms\Components\Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->native(false)
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Tabs\Tab::make('Files')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->openable()
                                    ->panelLayout('grid')
                                    ->downloadable()
                                    ->previewable()
                                    ->multiple()
                                    ->reorderable()
                                    ->disk('pets')
                                    ->collection('pets')
                                    ->maxFiles(100)
                            ]),
                        /*Tabs\Tab::make('Tab 3')
        ->schema([
            // ...
        ]),
        */
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
