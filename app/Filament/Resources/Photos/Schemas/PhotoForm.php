<?php

namespace App\Filament\Resources\Photos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->relationship('plan', 'title')
                    ->required(),
                TextInput::make('path')
                    ->required(),
            ]);
    }
}
