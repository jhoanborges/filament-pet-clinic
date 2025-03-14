<?php

namespace App\Filament\Doctor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Grid;
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

    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->products->count();
    }





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
                                ->avatar()
                                ->image()
                                ->imageEditor(),

                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),


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
                                ->disk('products')
                                ->collection('products')
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

                Tables\Columns\TextColumn::make('description')
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
