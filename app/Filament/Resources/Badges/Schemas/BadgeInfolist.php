<?php

namespace App\Filament\Resources\Badges\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BadgeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('icon'),
                TextEntry::make('criteria')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
