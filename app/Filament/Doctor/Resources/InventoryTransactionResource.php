<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Models\InventoryTransaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Doctor\Resources\InventoryTransactionResource\Pages;
use App\Filament\Doctor\Resources\InventoryTransactionResource\RelationManagers;

class InventoryTransactionResource extends Resource
{
    protected static ?string $model = InventoryTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';

    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->inventoryTransactions->count();
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('reference')
                ->unique()
                ->required(),

                Select::make('type')
                    ->options([
                        'entry' => 'Entrada',
                        'exit' => 'Salida',
                    ])
                    ->required(),

                    Repeater::make('products')
                    ->relationship('products')
                    ->schema([
                        Select::make('product_id')
                            ->options(Product::pluck('name', 'id')->toArray())
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->color(fn($record) => $record->type === 'entry' ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInventoryTransactions::route('/'),
            'create' => Pages\CreateInventoryTransaction::route('/create'),
            'edit' => Pages\EditInventoryTransaction::route('/{record}/edit'),
        ];
    }
}
