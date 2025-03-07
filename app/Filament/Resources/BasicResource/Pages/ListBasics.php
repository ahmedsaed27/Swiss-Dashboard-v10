<?php

namespace App\Filament\Resources\BasicResource\Pages;

use App\Filament\Resources\BasicResource;
use App\Models\Basic;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBasics extends ListRecords
{
    protected static string $resource = BasicResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }

    protected function getHeaderActions(): array
    {
        return Basic::count() === 0
            ? [static::makeCreateAction()]
            : []; // Hide create button if a record exists
    }
}
