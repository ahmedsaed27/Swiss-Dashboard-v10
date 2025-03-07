<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Students';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Students';

    protected static ?string $pluralModelLabel = 'Students';

    protected static ?string $modelLabel = 'Student';

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'username', 'email', 'contact_number'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'fullname' => $record->first_name . ' ' . $record->last_name,
            'username' => $record->username,
            'email' => $record->email,
            'contact number' => $record->contact_number,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return UsersResource::getUrl('edit', ['record' => $record]);
    }

    // public static function form(Forms\Form $form): Forms\Form
    // {
    //     return $form
    //         ->schema([
    //             Wizard::make(self::getSteps())
    //                 ->startOnStep(1)
    //                 ->contained(false)
    //                 ->skippable(false),
    //         ])
    //         ->columns(null);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                ImageColumn::make('image')->label('Profile Picture')->circular(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('contact_number')->sortable()->searchable(),
                TextColumn::make('address')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('state')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('country')->searchable()->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('email_verified_status')
                ->label('Email verified')
                ->boolean()
                ->getStateUsing(fn ($record) => !is_null($record->email_verified_at))
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),

                TextColumn::make('status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),

                TextColumn::make('edit_profile_status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                SelectFilter::make('edit_profile_status')
                    ->label('Edit Profile Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                Action::make('verifyEmail')
                ->label('Verify Email')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->hidden(fn ($record) => !is_null($record->email_verified_at)) // Hide if already verified
                ->action(function ($record) {
                    $record->update(['email_verified_at' => Carbon::now()]);
                }),


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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }


    public static function personalInformationStep(): array
    {
        return [
            TextInput::make('first_name')->required(),
            TextInput::make('last_name')->required(),
            FileUpload::make('image')->image(),
        ];
    }

    public static function accountDetailsStep(): array
    {
        return [
            TextInput::make('username')->required()->unique(User::class, 'username', ignoreRecord: true),
            TextInput::make('email')->email()->required()->unique(User::class, 'email', ignoreRecord: true),
            TextInput::make('password')->password()->required(function (?User $record) {
                return $record == null;
            })->confirmed()->revealable(),
            TextInput::make('password_confirmation')->password()->required(function (?User $record) {
                return $record == null;
            })->label('Confirm Password'),

        ];
    }

    public static function contactInformationStep(): array
    {
        return [
            TextInput::make('contact_number')->required()->numeric(),
            TextInput::make('address')->required(),
            TextInput::make('city')->required(),
            TextInput::make('state')->required(),
            TextInput::make('country')->required(),
        ];
    }

    public static function statusAndVerificationStep(): array
    {
        return [
            Select::make('status')->options([
                1 => 'Active',
                0 => 'inactive',
            ])->required(),
            TextInput::make('verification_token')->hidden(),
            Select::make('edit_profile_status')->options([
                1 => 'Active',
                0 => 'inactive',
            ])->required(),
        ];
    }
}
