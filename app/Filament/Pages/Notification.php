<?php

namespace App\Filament\Pages;

use App\Models\Guest;
use App\Notifications\PushNotification;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class Notification extends Page implements HasForms
{
    use InteractsWithForms , HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static string $view = 'filament.pages.notification';

    protected static ?string $navigationGroup = 'Notifications';

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?string $pluralModelLabel = 'Notifications';

    protected static ?string $modelLabel = 'Notification';

    public ?array $formData = [];

    public function mount()
    {
        $this->form->fill([
            'title' => '',
            'message' => '',
            'button_name' => '',
            'button_url' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('button_name')->required(),
                    TextInput::make('button_url')->required()->url(),
                    MarkdownEditor::make('message')->required(),
                ])
        ])
            ->statePath('formData');
    }

    public function create(): void
    {
        $guests = Guest::all();

        $title = $this->formData['title'];
        $message = $this->formData['message'];
        $buttonName = $this->formData['button_name'];
        $buttonURL = $this->formData['button_url'];

        NotificationFacade::send($guests, new PushNotification($title, $message, $buttonName, $buttonURL));

        $this->notify('success', 'Notification Sent successfully!');
    }
}
