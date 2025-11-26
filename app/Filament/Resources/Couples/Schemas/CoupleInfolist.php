<?php

namespace App\Filament\Resources\Couples\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CoupleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Pareja')
                    ->schema([
                        TextEntry::make('join_code')
                            ->label('Código de Unión')
                            ->copyable()
                            ->copyMessage('Código copiado')
                            ->copyMessageDuration(1500),
                        TextEntry::make('users.name')
                            ->label('Usuarios')
                            ->badge()
                            ->separator(','),
                        TextEntry::make('users.email')
                            ->label('Emails')
                            ->badge()
                            ->separator(','),
                    ]),
                Section::make('Estadísticas')
                    ->schema([
                        TextEntry::make('plans_count')
                            ->label('Total de Planes')
                            ->default(fn ($record) => $record->plans()->count()),
                        TextEntry::make('created_at')
                            ->label('Creado')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Actualizado')
                            ->dateTime(),
                    ])
                    ->collapsible(),
            ]);
    }
}
