<?php

namespace App\Filament\Resources\Couples\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CoupleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('join_code')
                    ->required(),
            ]);
    }
}
