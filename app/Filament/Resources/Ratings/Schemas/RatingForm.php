<?php

namespace App\Filament\Resources\Ratings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RatingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->relationship('plan', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('fun')
                    ->required()
                    ->numeric(),
                TextInput::make('emotional_connection')
                    ->required()
                    ->numeric(),
                TextInput::make('organization')
                    ->required()
                    ->numeric(),
                TextInput::make('value_for_money')
                    ->required()
                    ->numeric(),
                TextInput::make('overall')
                    ->required()
                    ->numeric(),
                Textarea::make('comment')
                    ->columnSpanFull(),
            ]);
    }
}
