<?php

namespace App\Filament\Resources\Couples\Pages;

use App\Filament\Resources\Couples\CoupleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCouples extends ListRecords
{
    protected static string $resource = CoupleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
