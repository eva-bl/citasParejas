<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('icon')
                    ->label('Icono (Emoji)')
                    ->helperText('Puedes usar un emoji como 🍕, 🎬, 🏖️, etc.')
                    ->maxLength(10),
                ColorPicker::make('color')
                    ->label('Color')
                    ->helperText('Color para identificar la categoría'),
            ]);
    }
}
