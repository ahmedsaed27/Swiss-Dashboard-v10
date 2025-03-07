<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationGroup = 'Instructor';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->name,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return InstructorResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Info')
                                ->schema([
                                    TextInput::make('name')->label('Name')->required(),

                                    Select::make('language_id')
                                        ->label('Language')
                                        ->options(Language::query()->pluck('code', 'id'))
                                        ->searchable()
                                        ->required(),

                                    TextInput::make('occupation')->label('Occupation')->required()->columnSpan(2),

                                    MarkdownEditor::make('description')->label('Description')->columnSpan(2),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make('Images & Featured')
                                ->schema([
                                    Toggle::make('is_featured')->required()->default(true),
                                    
                                    FileUpload::make('image')->required()->image()->disk('public'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('instructorLang.code')->label('Lang')->searchable()->badge(),
                TextColumn::make('occupation')->label('Occupation')->limit(30)->toggleable(isToggledHiddenByDefault :true),
                TextColumn::make('description')->label('Description')->listWithLineBreaks()->limit(30)->toggleable(isToggledHiddenByDefault :true),
                ToggleColumn::make('is_featured')->label('Is Featured'),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->relationship('instructorLang', 'code')
                    ->attribute('language_id')
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
