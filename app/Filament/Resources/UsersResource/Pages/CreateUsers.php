<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateUsers extends CreateRecord
{
    protected static string $resource = UsersResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                ->startOnStep(1)
                ->cancelAction($this->getCancelFormAction())
                ->submitAction($this->getSubmitFormAction())
                // ->skippable($this->hasSkippableSteps())
                ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [

            Step::make('Order Details')
            ->schema([
                Section::make()->schema(UsersResource::personalInformationStep())->columns(2),
            ]),

            Step::make('Account Details')
            ->schema([
                Section::make()->schema(UsersResource::accountDetailsStep())->columns(2),
            ]),

            Step::make('Contact Information')
            ->schema([
                Section::make()->schema(UsersResource::contactInformationStep())->columns(2),
            ]),

            Step::make('Status & Verification')
            ->schema([
                Section::make()->schema(UsersResource::statusAndVerificationStep())->columns(2),
            ]),
        ];
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        Notification::make()
            ->title('New User Created')
            ->icon('heroicon-o-user')
            ->body("**{$user->first_name} {$user->last_name} has been registered.**")
            ->actions([
                Action::make('View')
                    ->url(UsersResource::getUrl('edit', ['record' => $user]))
            ])
            ->sendToDatabase(auth()->guard('admin')->user());
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
