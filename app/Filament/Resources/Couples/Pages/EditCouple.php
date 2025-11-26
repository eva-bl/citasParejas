<?php

namespace App\Filament\Resources\Couples\Pages;

use App\Filament\Resources\Couples\CoupleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCouple extends EditRecord
{
    protected static string $resource = CoupleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
