<?php

namespace App\Filament\Resources\CourseEnrolmentResource\Pages;

use App\Filament\Resources\CourseEnrolmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListCourseEnrolments extends ListRecords
{
    protected static string $resource = CourseEnrolmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All')->label('All'),
            'Completed' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'completed')),
            'Pending' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'pending')),
            'Rejected' => Tab::make()->query(fn ($query) => $query->where('payment_status', 'rejected')),
        ];
    }
}
