<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoursesResource\Pages;
use App\Filament\Resources\CoursesResource\RelationManagers\InformationRelationManager;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Model;

class CoursesResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Courses-Mangment';

    public static function getGloballySearchableAttributes(): array
    {
        return ['information.title' , 'information.courseCategory.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'title' => $record?->information()?->where('language_id' , Language::query()->where('code' , 'en')->first()->id)?->first()?->title ?? '' ,
            'category' => $record?->information()?->where('language_id' , Language::query()->where('code' , 'en')->first()->id)?->first()->courseCategory->name ?? '',
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CoursesResource::getUrl('edit', ['record' => $record]);
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3) // 3-column layout
                    ->schema([
                        // Main Form Sections (2 Columns)
                        Grid::make(1) // Nested grid for sections
                            ->columnSpan(2)
                            ->schema([
                                // Course Info Section
                                Section::make('Course Info')
                                    ->schema([
                                        TextInput::make('video_link')->label('Video Link')->required()->url(),
                                        
                                        TimePicker::make('duration')->label('Duration')->default('00.00.00')->required(),

                                        TextInput::make('min_quiz_score')->label('Minimum Quiz Score')->numeric()->default(0.0),
                                    ]),

                                // Pricing Section
                                Section::make('Pricing')
                                    ->schema([
                                        Select::make('pricing_type')
                                            ->label('Pricing Type')
                                            ->options(['free' => 'Free', 'premium' => 'Premium'])
                                            ->default('premium')
                                            ->live()
                                            ->required(),

                                        TextInput::make('previous_price')
                                            ->label('Previous Price')
                                            ->numeric()
                                            ->nullable()
                                            ->hidden(fn(Get $get) => $get('pricing_type') === 'free'),

                                        TextInput::make('current_price')
                                            ->label('Current Price')
                                            ->numeric()
                                            ->nullable()
                                            ->hidden(fn(Get $get) => $get('pricing_type') === 'free'),
                                    ]),


                                Tabs::make('Certificate & Quiz')
                                    ->tabs([
                                        Tabs\Tab::make('English')
                                            ->icon('heroicon-m-language')
                                            ->badge('2')
                                            ->schema([
                                                TextInput::make('certificate_title_en')->label('Certificate Title')->required(),
                                                TextInput::make('meta_keywords_en')->label('Meta Keywords')->nullable(),

                                                Select::make('category_id_en')
                                                    ->label('Category')
                                                    ->options(CourseCategory::query()->get()->pluck('name', 'id')->toArray())
                                                    ->required(),

                                                TagsInput::make('features_en')->label('Features')->nullable(),

                                                TextInput::make('meta_description_en')->label('Meta Description')->nullable(),
                                                MarkdownEditor::make('certificate_text_en')->label('Certificate Text')->nullable()->columnSpan(2),
                                            ])->columns(2),
                                        Tabs\Tab::make('Arabic')
                                            ->icon('heroicon-m-language')
                                            ->badge('2')
                                            ->schema([
                                                TextInput::make('certificate_title_ar')->label('عنوان الشهاده')->required(),
                                                TextInput::make('meta_keywords_ar')->label('وصف الشهاده')->nullable(),

                                                Select::make('category_id_ar')
                                                    ->label('فئة')
                                                    ->options(CourseCategory::query()->get()->pluck('name', 'id')->toArray())
                                                    ->required(),

                                                TagsInput::make('features_ar')->label('سمات')->nullable(),

                                                TextInput::make('meta_description_ar')->label('الوصف التعريفي')->nullable(),
                                                MarkdownEditor::make('certificate_text_ar')->label('الشهاده')->nullable()->columnSpan(2),
                                            ])->columns(2),
                                    ]),

                            ]),

                        // Sidebar (1 Column)
                        Grid::make(1) // Nested grid for better section organization
                            ->columnSpan(1)
                            ->schema([
                                // Status Section
                                Section::make('Status')
                                    ->schema([
                                        Toggle::make('status')->label('Status')->default(false), // published , draft
                                        Toggle::make('is_featured')->label('Featured')->default(false), // yes , no
                                        Toggle::make('certificate_status')->label('Certificate Enabled')->default(true),
                                        Toggle::make('video_watching')->label('Require Video Watching')->default(true),
                                        Toggle::make('quiz_completion')->label('Require Quiz Completion')->default(false),
                                    ]),

                                // Media Upload Section (New Separate Section)
                                Section::make('Media Uploads')
                                    ->schema([
                                        FileUpload::make('thumbnail_image')
                                            ->label('Thumbnail Image')
                                            ->image()
                                            ->required(),
                                        FileUpload::make('cover_image')
                                            ->label('Cover Image')
                                            ->image()
                                            ->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('information.title')
                    ->label('Certificate Title')
                    ->badge()
                    ->sortable()
                    ->searchable(),


                TextColumn::make('information.courseCategory.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('video_link')
                    ->label('Video Link')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duration')
                    ->label('Duration')
                    ->sortable(),

                TextColumn::make('min_quiz_score')
                    ->label('Min Quiz Score')
                    ->sortable(),

                TextColumn::make('pricing_type')
                    ->label('Pricing Type')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('previous_price')
                    ->label('Previous Price')
                    ->sortable()
                    ->badge(),

                TextColumn::make('current_price')
                    ->label('Current Price')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                BooleanColumn::make('status')
                    ->label('Published')
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('is_featured')
                    ->label('Featured')
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('certificate_status')
                    ->label('Certificate Enabled')
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('video_watching')
                    ->label('Require Video Watching')
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('quiz_completion')
                    ->label('Require Quiz Completion')
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('thumbnail_image')
                    ->label('Thumbnail')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('cover_image')
                    ->label('Cover Image')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('pricing_type')
                    ->options([
                        'free' => 'Free',
                        'premium' => 'Premium',
                    ]),

                TernaryFilter::make('status')
                    ->label('Published')
                    ->trueLabel('Published')
                    ->falseLabel('Draft')
                    ->nullable(),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->nullable(),

                TernaryFilter::make('certificate_status')
                    ->label('Certificate Enabled')
                    ->trueLabel('Enabled')
                    ->falseLabel('Disabled')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            InformationRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourses::route('/create'),
            'edit' => Pages\EditCourses::route('/{record}/edit'),
        ];
    }
}
