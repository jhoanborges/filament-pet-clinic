<?php

namespace App\Filament\Doctor\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use App\Models\Note;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('media')
                    ->openable()
                    ->panelLayout('grid')
                    ->downloadable()
                    ->preserveFilenames()
                    ->previewable()
                    ->multiple()
                    ->reorderable()
                    ->disk('r2')
                    ->collection('appointments')
                    ->maxFiles(100)
                    ->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('body')
                    // ->formatStateUsing(fn ($state) => strip_tags($state))
                    ->html()
                    ->limit(255)
                    ->description(fn(Note $note) => $note->created_at->format('M d, Y'))
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
