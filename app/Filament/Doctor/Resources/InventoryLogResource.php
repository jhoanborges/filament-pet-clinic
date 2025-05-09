<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\InventoryLog;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Doctor\Resources\InventoryLogResource\Pages;
use App\Filament\Doctor\Resources\InventoryLogResource\RelationManagers;

class InventoryLogResource extends Resource
{
    protected static ?string $model = InventoryLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
    protected static ?int $navigationSort = 5;

    // Traducciones para el nombre del recurso
    protected static ?string $label = 'Inventory Log';
    protected static ?string $pluralLabel = 'Inventory Logs';

/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->inventoryLog->count();
    }
*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('action')
                    ->label(__('Action'))
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('Delete')),
                ]),
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
            'index' => Pages\ListInventoryLogs::route('/'),
            'create' => Pages\CreateInventoryLog::route('/create'),
            'edit' => Pages\EditInventoryLog::route('/{record}/edit'),
        ];
    }
}
