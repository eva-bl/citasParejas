<?php

namespace App\Filament\Resources\Couples\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CoupleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('join_code'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
