<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('couple_id')
                    ->relationship('couple', 'id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('location'),
                TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('overall_avg')
                    ->numeric(),
                TextInput::make('fun_avg')
                    ->numeric(),
                TextInput::make('emotional_connection_avg')
                    ->numeric(),
                TextInput::make('organization_avg')
                    ->numeric(),
                TextInput::make('value_for_money_avg')
                    ->numeric(),
                TextInput::make('ratings_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('photos_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_rated_at'),
            ]);
    }
}
