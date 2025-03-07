<?php

namespace App\Filament\Resources\BasicResource\Pages;

use App\Filament\Resources\BasicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBasic extends EditRecord
{
    protected static string $resource = BasicResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function canDelete($record): bool
    {
        return false; // Prevent deletion
    }
}
