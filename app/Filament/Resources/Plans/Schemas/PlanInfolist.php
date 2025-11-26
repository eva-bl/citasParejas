<?php

namespace App\Filament\Resources\Plans\Schemas;

use App\Models\Plan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('couple.id')
                    ->label('Couple'),
                TextEntry::make('title'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('overall_avg')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fun_avg')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('emotional_connection_avg')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('organization_avg')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('value_for_money_avg')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('ratings_count')
                    ->numeric(),
                TextEntry::make('photos_count')
                    ->numeric(),
                TextEntry::make('last_rated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Plan $record): bool => $record->trashed()),
            ]);
    }
}
