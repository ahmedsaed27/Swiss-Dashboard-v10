<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Filament\Resources\GroupResource\RelationManagers\StudentsRelationManager;
use App\Models\Course;
use App\Models\Group;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Groups';


    public static function getGloballySearchableAttributes(): array
    {
        return ['code' , 'status' , 'instructor.name' , 'course.information.title'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'code' => $record->code,
            'status' => $record->status,
            'instructor' => $record->instructor->name,
            'course' => $record?->course?->information()?->where('language_id' , Language::query()->where('code' , 'en')->first()->id)?->first()?->title ?? '',
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return GroupResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Group Details')->schema([

                    Select::make('course_id')
                        ->label('Course')
                        ->options(
                            Course::with(['information' => function ($query) {
                                $query->where('language_id', \App\Models\Language::where('code', 'en')->value('id'));
                            }])->get()
                                ->mapWithKeys(function ($course) {
                                    if ($course->information->isNotEmpty()) {
                                        return [$course->id => $course->information->first()->title ?? 'Unknown Course'];
                                    }
                                    return [$course->id => 'Unknown Course'];
                                })
                        )
                        ->searchable()
                        ->live()
                        ->required(),

                    Select::make('instructor_id')
                        ->label('Instructor')
                        ->options(function (Get $get) {
                            if ($get('course_id')) {
                                return \App\Models\Instructor::whereHas('courses', function ($query) use ($get) {
                                    $query->where('course_id', $get('course_id'));
                                })->pluck('name', 'id')->toArray(); // ✅ Return the array
                            }
                            return []; // ✅ Always return an array (even empty)
                        })
                        ->searchable()
                        ->reactive()
                        ->nullable(),




                    Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'Completed' => 'Completed',
                            'Postponed' => 'Postponed',
                        ])
                        ->default('Pending')
                        ->required(),

                    TextInput::make('max_students')
                        ->label('Max Students')
                        ->numeric()
                        ->default(40),

                    TextInput::make('current_count')
                        ->label('Current Student Count')
                        ->numeric()
                        ->default(0),

                    DatePicker::make('start_date')
                        ->label('Start Date')
                        ->nullable(),

                    DatePicker::make('end_date')
                        ->label('End Date')
                        ->nullable(),

                    DatePicker::make('postponed_at')
                        ->label('Postponed Date')
                        ->nullable(),
                ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->sortable()->searchable(),
                TextColumn::make('course.information.title')->badge()->label('Course')->sortable(),
                TextColumn::make('instructor.name')->label('Instructor')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('max_students')->label('Max Students'),
                TextColumn::make('current_count')->label('Current Count'),
                TextColumn::make('start_date')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_date')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postponed_at')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Completed' => 'Completed',
                        'Postponed' => 'Postponed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
