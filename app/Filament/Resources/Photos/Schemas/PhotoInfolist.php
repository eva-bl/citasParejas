<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Photo;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhotoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('plan.title')
                    ->label('Plan'),
                TextEntry::make('path'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Photo $record): bool => $record->trashed()),
            ]);
    }
}
