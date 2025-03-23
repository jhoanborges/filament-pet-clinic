<?php

namespace App\Filament\Doctor\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use App\Models\Note;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;

use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
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
                Split::make([
                    Tables\Columns\TextColumn::make('body')
                    ->html()
                    ->limit(255)
                    ->description(fn(Note $note) => $note->created_at->format('M d, Y'))
                ]),
                Panel::make([
                    Stack::make([
                        ViewColumn::make('media')
                        ->label('Files')
                        ->view('filament.tables.columns.media-files')
                        ->getStateUsing(function (Note $record) {
                            return $record->getMedia("*")
                                ->map(function ($media) {
                                    return [
                                        'url' => $media->getUrl(),
                                        'name' => $media->name,
                                        'file_name' => $media->file_name,
                                        'mime_type' => $media->mime_type,
                                        'size' => $media->human_readable_size,
                                        'extension' => Str::afterLast($media->file_name, '.'),
                                    ];
                                })
                                ->toArray();
                        }),
                    ]),
                ])->collapsible(),

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
