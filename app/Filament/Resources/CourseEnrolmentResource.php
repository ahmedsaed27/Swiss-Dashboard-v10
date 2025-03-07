<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrolmentResource\Pages;
use App\Models\CourseEnrolment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\{Section, TextInput, Select, Grid, FileUpload};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Model;

class CourseEnrolmentResource extends Resource
{
    protected static ?string $model = CourseEnrolment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Courses-Mangment';

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.username' , 'order_id' ,'billing_first_name' , 'billing_last_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'order_id' => $record->order_id,
            'username' => $record->user->username,
            'fullname' => $record->user->first_name . ' ' . $record->user->last_name,
            'billing first name' => $record->billing_first_name,
            'billing last name' => $record->billing_last_name,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CourseEnrolmentResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('User & Course Details')->schema([
                    Select::make('user_id')
                        ->relationship('user', 'username')
                        ->searchable()
                        ->required(),

                    Select::make('course_id')
                        ->relationship('course.information', 'title')
                        ->searchable()
                        ->required(),

                    TextInput::make('order_id')->unique()->required(),
                ])->columns(2),

                Section::make('Billing Details')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('billing_first_name')->required(),
                        TextInput::make('billing_last_name')->required(),
                    ]),
                    TextInput::make('billing_email')->email()->required(),
                    TextInput::make('billing_contact_number')->tel()->required(),
                    TextInput::make('billing_address')->columnSpanFull(),
                    Grid::make(3)->schema([
                        TextInput::make('billing_city')->required(),
                        TextInput::make('billing_state')->nullable(),
                        TextInput::make('billing_country')->required(),
                    ]),
                ]),

                Section::make('Payment Information')->schema([
                    Grid::make(3)->schema([
                        TextInput::make('course_price')->numeric()->prefix('$')->nullable(),
                        TextInput::make('discount')->numeric()->prefix('$')->nullable(),
                        TextInput::make('grand_total')->numeric()->prefix('$')->required(),
                    ]),
                    Grid::make(2)->schema([
                        Select::make('currency_text_position')
                            ->options(['left' => 'Left', 'right' => 'Right'])
                            ->nullable(),

                        Select::make('currency_symbol_position')
                            ->options(['left' => 'Left', 'right' => 'Right'])
                            ->nullable(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('currency_text')->nullable(),
                        TextInput::make('currency_symbol')->nullable(),
                    ]),
                ]),

                Section::make('Payment & Status')->schema([
                    Select::make('payment_method')
                        ->options([
                            'MyFatoorah' => 'MyFatoorah',
                            'Cash' => 'Cash',
                            'Kashier' => 'Kashier',
                        ])
                        ->searchable()
                        ->nullable(),

                    Select::make('gateway_type')
                        ->options([
                            'online' => 'Online',
                            'manual' => 'Manual',
                        ])
                        ->nullable(),

                        Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'rejected' => 'Rejected',
                        ])
                        ->default('pending')
                        ->required()

                ]),

                Section::make('Attachments')->schema([
                    FileUpload::make('attachment')->nullable(),
                    FileUpload::make('invoice')->nullable(),
                ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->searchable()->sortable(),
                TextColumn::make('user.username')
                    ->label('User')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable()
                    ->action(fn($record) => redirect()->route('filament.admin.resources.users.edit', ['record' => $record->user_id])),

                TextColumn::make('course.information.title')->badge()->label('Course')->sortable()->searchable(),

                
                TextColumn::make('payment_status')
                ->label('Payment Status')
                ->formatStateUsing(fn($state) => match ($state) {
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'rejected' => 'Rejected',
                })
                ->badge()
                ->color(fn($state) => match ($state) {
                    'pending' => 'warning',
                    'completed' => 'success',
                    'rejected' => 'danger',
                }),


                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'MyFatoorah' => '💳 MyFatoorah',
                        'Cash' => '💵 Cash',
                        'Kashier' => '🏦 Kashier',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),


                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('EGY')
                    ->sortable()
                    ->summarize(
                        Sum::make()
                            ->label('Total')
                            ->query(fn($query) => $query->where('payment_status', 'completed'))
                    )

            ])
            ->filters([
                //
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
            'index' => Pages\ListCourseEnrolments::route('/'),
            'create' => Pages\CreateCourseEnrolment::route('/create'),
            'edit' => Pages\EditCourseEnrolment::route('/{record}/edit'),
        ];
    }
}
