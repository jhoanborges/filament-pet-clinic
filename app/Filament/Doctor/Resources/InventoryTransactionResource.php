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
use App\Models\Inventory;

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
                    ->label(__('Reference'))
                    ->unique()
                    ->default(function () {
                        $lastOrder = InventoryTransaction::orderBy('id', 'desc')->first();
                        if ($lastOrder) {
                            return 'INVTR-'.$lastOrder->id + 1;
                        } else {
                            return 'INVTR-1';
                        }
                    })
                    ->required(),
                Select::make('type')
                    ->label(__('Type'))
                    ->options([
                        'entry' => __('Entry'),
                        'exit' => __('Exit'),
                    ])
                    ->required(),
                Repeater::make('products')
                    ->label(__('Products'))
                    ->relationship('products')
                    ->schema([
                        TextInput::make('sku')
                            ->label(__('SKU'))
                            ->dehydrated(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) {
                                $record = \App\Models\Product::where('sku', $state)->first();
                                if ($record) {
                                    $set('product_id', $record->id);
                                } else {
                                    Notification::make()
                                        ->color('warning')
                                        ->warning()
                                        ->title(__('Product error!'))
                                        ->body(__('The selected product does not exist.'))
                                        ->persistent(false)
                                        ->duration(10000)
                                        ->icon('heroicon-o-x-circle')
                                        ->actions([
                                            Action::make(__('View Products'))
                                                ->button()
                                                ->url(route('filament.doctor.resources.products.index', ['tenant' => Filament::getTenant()->id]), shouldOpenInNewTab: true),
                                        ])
                                        ->send();
                                }
                            }),
                        Select::make('product_id')
                            ->label(__('Product'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options(Product::all()->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $record = \App\Models\Product::where('id', $state)->first();
                                if ($record) {
                                    $set('sku', $record->sku);
                                }
                            }),
                        TextInput::make('quantity')
                            ->label(__('Quantity'))
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
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn($record) => $record->type === 'entry' ? 'success' : 'danger')
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
            'index' => Pages\ListInventoryTransactions::route('/'),
            'create' => Pages\CreateInventoryTransaction::route('/create'),
            'edit' => Pages\EditInventoryTransaction::route('/{record}/edit'),
        ];
    }
}
