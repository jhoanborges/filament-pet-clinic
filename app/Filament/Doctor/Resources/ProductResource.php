<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Doctor\Resources\ProductResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Doctor\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantOwnershipRelationshipName = 'clinics';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 2;
/*
    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->products->count();
    }*/

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Product Information')
                        ->icon('phosphor-shopping-cart-light')
                        ->schema([

                            Forms\Components\FileUpload::make('image')
                                ->columnSpanFull()
                                //->disk('products')
                                //->directory('products')
                                ->openable()
                                ->downloadable()
                               ->avatar()
                                ->image()
                                ->imageEditor(),

                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                                Select::make('category_id')
                                ->label('Category')
                                ->preload()
                                ->options(ProductCategory::all()->pluck('name', 'id'))
                                ->searchable(),
                                //->required(),


                            \Filament\Forms\Components\TextInput::make('price')
                                ->suffix(config('money.defaults.currency'))
                                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2),


                            TextInput::make('sku')
                                ->unique(ignoreRecord: true)
                                ->nullable()
                                ->maxLength(255),

                            RichEditor::make('description')
                                ->columnSpanFull()
                                ->nullable(),

                        ])->columns(2),
                    Tabs\Tab::make('Images')
                        ->icon('phosphor-images')

                        ->schema([

                            SpatieMediaLibraryFileUpload::make('media')
                                ->image()
                                ->openable()
                                ->panelLayout('grid')
                                ->downloadable()
                                ->previewable()
                                ->multiple()
                                ->reorderable()
                                ->disk('r2')
                                ->directory('products')
                                ->maxFiles(100)

                        ]),


                ])
                ->persistTab()
                ->id('products-tabs')
                ->persistTabInQueryString('products-tabs')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                ImageColumn::make('image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sku')
                    ->sortable()
                    ->searchable(),
                    TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state),

                Tables\Columns\TextColumn::make('category.name')
                    ->limit(50)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
