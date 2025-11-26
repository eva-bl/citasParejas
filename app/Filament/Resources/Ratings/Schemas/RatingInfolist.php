<?php

namespace App\Filament\Resources\Ratings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RatingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plan.title')
                    ->label('Plan'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('fun')
                    ->numeric(),
                TextEntry::make('emotional_connection')
                    ->numeric(),
                TextEntry::make('organization')
                    ->numeric(),
                TextEntry::make('value_for_money')
                    ->numeric(),
                TextEntry::make('overall')
                    ->numeric(),
                TextEntry::make('comment')
                    ->placeholder('-')
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
