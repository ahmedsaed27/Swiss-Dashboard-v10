<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationGroup = 'Courses-Mangment';

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'type', 'start_date' , 'end_date' ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->name,
            'code' => $record->code,
            'type' => $record->type,
            'start date' => $record->start_date,
            'end date' => $record->end_date,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CouponResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Coupon Info')
                        ->description('Fill in the coupon details below. If you want this coupon to apply to all courses, you can leave the "Courses" field empty.')
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required(),

                            TextInput::make('code')
                                ->label('Code')
                                ->required()
                                ->default(fn() => strtoupper(Str::random(8)))
                                ->unique(Coupon::class, 'code', ignoreRecord: true),

                            Select::make('type')
                                ->label('Coupon Type')
                                ->options([
                                    'Percentage' => 'percentage',
                                    'Fixed' => 'fixed',
                                ])
                                ->required(),

                            TextInput::make('value')
                                ->label('Value')
                                ->numeric()
                                ->required(),

                            DatePicker::make('start_date')
                                ->date()
                                ->required(),

                            DatePicker::make('end_date')
                                ->date()
                                ->after('start_date')
                                ->required(),


                            Select::make('courses')
                                ->label('Courses')
                                ->multiple()
                                ->options([
                                    1 => 'frontend',
                                    2 => 'backend',
                                ])
                                ->nullable()
                                ->helperText(str('If you want this coupon to apply to all courses , **you can leave the "Courses" field empty.**')->inlineMarkdown()->toHtmlString()),
                        ])
                        ->columns(2)
                        ->columnSpan('full'),
                ])->columnSpan('full'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->label('ID'),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('type')
                    ->label('Coupon Type')
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Coupon Type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    ]),

                Filter::make('active')
                    ->label('Active Coupons')
                    ->query(fn(Builder $query) => $query->where('end_date', '>=', now())),

                Filter::make('expired')
                    ->label('Expired Coupons')
                    ->query(fn(Builder $query) => $query->where('end_date', '<', now())),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
