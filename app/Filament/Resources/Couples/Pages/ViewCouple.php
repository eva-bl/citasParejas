<?php

namespace App\Filament\Resources\Couples\Pages;

use App\Filament\Resources\Couples\CoupleResource;
use App\Models\Couple;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCouple extends ViewRecord
{
    protected static string $resource = CoupleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('regenerate_join_code')
                ->label('Regenerar Código')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Regenerar Código de Unión')
                ->modalDescription('¿Estás seguro de que quieres regenerar el código de unión? El código actual dejará de funcionar.')
                ->action(function (Couple $record) {
                    $record->update([
                        'join_code' => Couple::generateJoinCode(),
                    ]);
                    
                    Notification::make()
                        ->title('Código regenerado exitosamente')
                        ->success()
                        ->send();
                }),
            EditAction::make(),
        ];
    }
}
