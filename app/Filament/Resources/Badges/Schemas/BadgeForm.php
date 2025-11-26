<?php

namespace App\Filament\Resources\Badges\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BadgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('icon')
                    ->required(),
                Textarea::make('criteria')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
