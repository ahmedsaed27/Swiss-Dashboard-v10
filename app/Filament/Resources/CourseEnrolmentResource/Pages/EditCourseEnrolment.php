<?php

namespace App\Filament\Resources\CourseEnrolmentResource\Pages;

use App\Filament\Resources\CourseEnrolmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseEnrolment extends EditRecord
{
    protected static string $resource = CourseEnrolmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
