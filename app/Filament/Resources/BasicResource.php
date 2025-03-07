<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BasicResource\Pages;
use App\Filament\Resources\BasicResource\RelationManagers;
use App\Models\Basic;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BasicResource extends Resource
{
    protected static ?string $model = Basic::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Basic Settings';

    protected static ?string $pluralModelLabel = 'Basic Settings';

    protected static ?string $modelLabel = 'Basic Setting';

    public static function canCreate(): bool
    {
        return static::$model::count() === 0;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Section::make('General Settings')->schema([
                        TextInput::make('website_title')->label('Website Title')->required(),
                        TextInput::make('email_address')->label('Email Address')->email()->required(),
                        TextInput::make('contact_number')->label('Contact Number'),
                        TextInput::make('address')->label('Address'),
                        TextInput::make('latitude')->label('Latitude')->numeric(),
                        TextInput::make('longitude')->label('Longitude')->numeric(),
                    ])->columnSpan(1),

                   

                    Section::make('Theme Settings')->schema([
                        ToggleButtons::make('theme_version')->label('Theme Version')->options([
                            0 => 'Light',
                            1 => 'Dark',
                        ])->inline(),
                        ColorPicker::make('primary_color')->label('Primary Color'),
                        ColorPicker::make('secondary_color')->label('Secondary Color'),
                        ColorPicker::make('breadcrumb_overlay_color')->label('Breadcrumb Overlay Color'),
                        TextInput::make('breadcrumb_overlay_opacity')->label('Breadcrumb Overlay Opacity')->numeric(),
                    ])->columnSpan(1),
                ]),

                Grid::make(2)->schema([
                    Section::make('SMTP Settings')->schema([
                        Toggle::make('smtp_status')->label('Enable SMTP'),
                        TextInput::make('smtp_host')->label('SMTP Host'),
                        TextInput::make('smtp_port')->label('SMTP Port')->numeric(),
                        Select::make('encryption')->label('Encryption')->options([
                            'ssl' => 'SSL',
                            'tls' => 'TLS',
                        ]),
                        TextInput::make('smtp_username')->label('SMTP Username'),
                        TextInput::make('smtp_password')->label('SMTP Password')->password(),
                        TextInput::make('from_mail')->label('From Email'),
                        TextInput::make('from_name')->label('From Name'),
                    ])->columnSpan(1),

                    Section::make('Logos & Images')->schema([
                        FileUpload::make('favicon')->label('Favicon')->image(),
                        FileUpload::make('logo')->label('Website Logo')->image(),
                        FileUpload::make('footer_logo')->label('Footer Logo')->image(),
                        FileUpload::make('maintenance_img')->label('Maintenance Image')->image(),
                        FileUpload::make('notification_image')->label('Notification Image')->image(),
                    ])->columnSpan(1),

                ]),

                Grid::make(2)->schema([
                    Section::make('Currency Settings')->schema([
                        TextInput::make('base_currency_symbol')->label('Currency Symbol'),
                        TextInput::make('base_currency_symbol_position')->label('Currency Symbol Position'),
                        TextInput::make('base_currency_text')->label('Currency Text'),
                        TextInput::make('base_currency_text_position')->label('Currency Text Position'),
                        TextInput::make('base_currency_rate')->label('Currency Rate')->numeric(),
                    ])->columnSpan(1),

                    Section::make('Google Settings')->schema([
                        Toggle::make('google_recaptcha_status')->label('Enable reCAPTCHA'),
                        TextInput::make('google_recaptcha_site_key')->label('Site Key'),
                        TextInput::make('google_recaptcha_secret_key')->label('Secret Key'),
                        TextInput::make('google_adsense_publisher_id')->label('AdSense Publisher ID'),
                    ])->columnSpan(1),
                ]),

                Grid::make(2)->schema([
                    Section::make('WhatsApp Settings')->schema([
                        Toggle::make('whatsapp_status')->label('Enable WhatsApp Support'),
                        TextInput::make('whatsapp_number')->label('WhatsApp Number'),
                        TextInput::make('whatsapp_header_title')->label('Header Title'),
                        Toggle::make('whatsapp_popup_status')->label('Enable Popup'),
                        MarkdownEditor::make('whatsapp_popup_message')->label('Popup Message'),
                    ])->columnSpan(1),

                    Section::make('Maintenance Mode')->schema([
                        Toggle::make('maintenance_status')->label('Enable Maintenance Mode'),
                        TextInput::make('bypass_token')->label('Bypass Token'),
                        MarkdownEditor::make('maintenance_msg')->label('Maintenance Message'),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('favicon')->circular(),
                ImageColumn::make('logo')->circular(),
                ImageColumn::make('footer_logo')->circular()->toggleable(isToggledHiddenByDefault:true),
                ImageColumn::make('maintenance_img')->circular()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('website_title')->label('Website Title')->searchable(),
                TextColumn::make('email_address')->label('Email Address'),
                TextColumn::make('contact_number')->label('Contact Number'),
                TextColumn::make('base_currency_symbol')->label('Currency Symbol'),
                TextColumn::make('whatsapp_number')->label('whatsapp Number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBasics::route('/'),
            'create' => Pages\CreateBasic::route('/create'),
            'edit' => Pages\EditBasic::route('/{record}/edit'),
        ];
    }
}
