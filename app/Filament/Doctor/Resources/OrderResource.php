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
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->orders->count();
    }


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
                            return 'ORD-1'; // If no records exist, start with 1
                        }
                    })
                    ->helperText('This ID must be unique')  
                    ->label('Referencia')
                    ->required()
                    ->maxLength(255),
/*
                TextInput::make('currency')
                    ->label('Moneda')
                    ->default('MXN')
                    ->required(),
*/
                Repeater::make('orderProducts')
                    ->relationship()
                    ->columnSpanFull()
                    ->schema([


                        TextInput::make('sku')
                            ->label('SKU')
                            ->live(onBlur: true)
                            //->icon('heroicon-o-arrow-right')          ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $record = \App\Models\Product::where('sku', $state)->first();
                                if ($record) {
                                    // dd($record);
                                    $set('product_id', $record->id);
                                    $set('quantity', 1);
                                    $set('quantity_available', $record->stock);
                                    $set('price', $record->price);
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
                            ->label('Cantidad Disponible')
                            ->numeric()
                            ->live()
                            ->disabled()
                            ->afterStateHydrated(function (TextInput $component, $state, Set $set, $record) {
                                // If we have a record with a product, get its stock
                                if ($record && $record->product) {
                                    $set('quantity_available', $record->product->stock);
                                }
                            }),

                        \Filament\Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                            ->required(),


                        \Filament\Forms\Components\TextInput::make('price')
                            ->suffix(config('money.defaults.currency'))
                            ->label('Precio')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->required(),
                    ])->columns(5),

                    RichEditor::make('notes')
                    ->columnSpanFull()
                    ->label('Notas')
                    ->nullable(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Moneda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
      
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
