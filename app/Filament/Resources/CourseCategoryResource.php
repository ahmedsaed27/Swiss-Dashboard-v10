<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseCategoryResource\Pages;
use App\Filament\Resources\CourseCategoryResource\RelationManagers;
use App\Models\CourseCategory;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class CourseCategoryResource extends Resource implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = CourseCategory::class;

    protected static ?string $navigationGroup = 'Courses-Mangment';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'serial_number'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->name,
            'slug' => $record->slug,
            'serial_number' => $record->serial_number,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CourseCategoryResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3) // Define a grid with 3 columns
                    ->schema([
                        Group::make([
                            Section::make('Category Info')
                                ->description('Category Info')
                                ->schema([
                                    Select::make('language_id')
                                        ->label('Language')
                                        ->options(Language::query()->pluck('code', 'id'))
                                        ->searchable()
                                        ->required(),
                                    
                                    ColorPicker::make('color')->required(),

                                    TextInput::make('name')->required(),

                                    TextInput::make('slug')->required(),

                                    TextInput::make('serial_number')->numeric()->required()->columnSpan(2),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make('Toggle')
                                ->description('status && is_featured')
                                ->schema([
                                    Toggle::make('status')->required()->default(false),

                                    Toggle::make('is_featured')->required()->default(false),

                                    // FileUpload::make('image')->required()->image()->columnSpan(2),
                                ])->columns(2),
                        ])->columnSpan(1),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),

                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('slug')->searchable()->sortable(),

                Tables\Columns\ColorColumn::make('color'),

                Tables\Columns\TextColumn::make('serial_number')->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('categoryLang.code')
                    ->label('Language')
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('language_id')
                    ->label('Language')
                    ->relationship('categoryLang', 'code'),
                // ->searchable(),


                SelectFilter::make('status')
                ->options([
                    true => 'Active',
                    false => 'Inactive',
                ])
                ->default(null),

                SelectFilter::make('is_featured')
                ->options([
                    true => 'Active',
                    false => 'Inactive',
                ])
                ->default(null)
            
                
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('serial_number', 'asc');
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
            'index' => Pages\ListCourseCategories::route('/'),
            'create' => Pages\CreateCourseCategory::route('/create'),
            'edit' => Pages\EditCourseCategory::route('/{record}/edit'),
        ];
    }
}
