<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Forms\Form;

class EditUsers extends EditRecord
{
    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep(1)
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
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

    protected function afterSave(): void
    {
        $user = $this->record;

        Notification::make()
            ->title('User Updated')
            ->icon('heroicon-o-user')
            ->body("**{$user->first_name} {$user->last_name} has been updated.**")
            ->actions([
                Action::make('View')
                    ->url(UsersResource::getUrl('edit', ['record' => $user])),
            ])
            ->sendToDatabase(auth()->guard('admin')->user());
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(!isset($data['password'])){
            unset($data['password']);
        }
    
        return $data;
    }   
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
