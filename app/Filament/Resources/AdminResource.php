<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationGroup = 'Admins';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'username', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'fullname' => $record->first_name . ' ' . $record->last_name,
            'username' => $record->username,
            'email' => $record->email,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return AdminResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    // Inputs (2 Columns)

                    Section::make('Admin Information')
                        ->schema([
                            TextInput::make('first_name')->required(),
                            TextInput::make('last_name')->required(),
                            TextInput::make('username')->required(),
                            TextInput::make('email')->email()->required(),
                            TextInput::make('password')
                                ->password()
                                ->required(fn($record) => !$record)
                                ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
                                ->dehydrated(fn($state) => filled($state)),

                            Select::make('roles')
                                ->label('Assign Role')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload()
                                ->searchable(),
                        ])
                        ->columns(2)
                        ->columnSpan(2),


                    Section::make('Profile Picture')
                        ->schema([
                            Toggle::make('status')->label('Active')->default(true),

                            FileUpload::make('image')->image()->nullable(),
                        ])
                        ->columnSpan(1),
                ]),

                // Roles Section Below (Full Width)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('username')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('roleNames')
                ->label('Roles')
                ->searchable()
                ->badge()
                ->separator(','),
                TextColumn::make('status')
                ->sortable()
                ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                ->badge()
                ->color(fn($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
