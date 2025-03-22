<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Models\InventoryTransaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Doctor\Resources\InventoryTransactionResource\Pages;
use App\Filament\Doctor\Resources\InventoryTransactionResource\RelationManagers;

class InventoryTransactionResource extends Resource
{
    protected static ?string $model = InventoryTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
    protected static ?int $navigationSort = 4;
/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->inventoryTransactions->count();
    }*/


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

                        TextInput::make('sku')
                        ->label('SKU')
                        ->dehydrated(false) //Exclude field from saving
                        ->live(onBlur: true)
                        //->icon('heroicon-o-arrow-right')          ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            $record = \App\Models\Product::where('sku', $state)->first();
                            if ($record) {
                                // dd($record);
                                $set('product_id', $record->id);
                            } else {
                                Notification::make()
                                    ->color('warning')
                                    ->warning()
                                    ->title('Product error!')
                                    ->body('The selected product does not exist.')
                                    ->persistent(false)
                                    ->duration(10000)
                                    ->icon('heroicon-o-x-circle')
                                    ->actions([
                                        Action::make('View Products')
                                            ->button()
                                            ->url(route('filament.doctor.resources.products.index', ['tenant' => Filament::getTenant()->id]), shouldOpenInNewTab: true),
                                    ])
                                    ->send();
                            }
                        }),

                        Select::make('product_id')
                        ->label('Product')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options(Product::all()->pluck('name', 'id'))
                        //->relationship('product', 'name')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            $record = \App\Models\Product::where('id', $state)->first();
                            if ($record) {
                                $set('sku', $record->sku);
                            }
                        }),


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
