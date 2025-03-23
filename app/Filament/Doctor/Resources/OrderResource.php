<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Doctor\Resources\OrderResource\Pages;
use App\Filament\Doctor\Resources\OrderResource\RelationManagers;
use App\Filament\Doctor\Resources\OrderResource\RelationManagers\ProductsRelationManager;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionProduct;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 1;
/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->orders->count();
    }
*/

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('reference')
                ->unique(Order::class, 'reference', ignoreRecord: true)
                ->default(function () {
                    $lastOrder = Order::orderBy('id', 'desc')->first();
                    if ($lastOrder) {
                        return 'ORD-'.$lastOrder->id + 1;
                    } else {
                        return 'ORD-1';
                    }
                })
                ->helperText(__('This ID must be unique'))
                ->label(__('Reference'))
                ->required()
                ->maxLength(255),
            Repeater::make('orderProducts')
                ->relationship()
                ->columnSpanFull()
                ->schema([
                    TextInput::make('sku')
                        ->label(__('SKU'))
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, $state) {
                            $record = \App\Models\Product::where('sku', $state)->first();
                            if ($record) {
                                $set('product_id', $record->id);
                                $set('quantity', 1);
                                $set('quantity_available', $record->stock);
                                $set('price', $record->price);
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
                        ->relationship('product', 'name')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            $record = \App\Models\Product::where('id', $state)->first();
                            if ($record) {
                                $set('quantity_available', $record->stock);
                                $set('sku', $record->sku);
                                $set('price', $record->price);
                            }
                        }),
                    TextInput::make('quantity_available')
                        ->label(__('Quantity Available'))
                        ->numeric()
                        ->live()
                        ->disabled()
                        ->afterStateHydrated(function (TextInput $component, $state, Set $set, $record) {
                            if ($record && $record->product) {
                                $set('quantity_available', $record->product->stock);
                            }
                        }),
                    \Filament\Forms\Components\TextInput::make('quantity')
                        ->label(__('Quantity'))
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('price')
                        ->suffix(config('money.defaults.currency'))
                        ->label(__('Price'))
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                        ->required(),
                ])->columns(5),
            RichEditor::make('notes')
                ->columnSpanFull()
                ->label(__('Notes'))
                ->nullable(),
        ]);
}


public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('reference')
                ->label(__('Reference'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('currency')
                ->label(__('Currency'))
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Created At'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\DeleteAction::make()
                ->label(__('Delete'))
                ->before(function (Order $order) {
                    $inventoryTransaction = InventoryTransaction::with('products')->where('order_id', $order->id);
                    InventoryTransactionProduct::where('inventory_transaction_id', $inventoryTransaction->first()->id)->delete();
                    $inventoryTransaction->delete();
                }),
            Tables\Actions\ViewAction::make()
                ->label(__('View')),
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
            //ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            //'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
