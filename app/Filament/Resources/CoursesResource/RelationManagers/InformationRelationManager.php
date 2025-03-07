<?php

namespace App\Filament\Resources\CoursesResource\RelationManagers;

use App\Filament\Resources\InstructorResource;
use App\Models\Instructor;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Model;

class InformationRelationManager extends RelationManager
{
    protected static string $relationship = 'instructors';

    protected static ?string $title = 'Course Instructors';


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                FileUpload::make('image')->required()->image()->disk('public'),
                TextInput::make('name')->label('Name')->required(),

                Select::make('language_id')
                    ->label('Language')
                    ->options(Language::query()->pluck('code', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('occupation')->label('Occupation')->required()->columnSpan(2),

                Toggle::make('is_featured')->required()->default(true),


                MarkdownEditor::make('description')->label('Description')->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image')->circular()->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Instructor Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
