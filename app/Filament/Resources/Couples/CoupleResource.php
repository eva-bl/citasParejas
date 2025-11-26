<?php

namespace App\Filament\Resources\Couples;

use App\Filament\Resources\Couples\Pages\CreateCouple;
use App\Filament\Resources\Couples\Pages\EditCouple;
use App\Filament\Resources\Couples\Pages\ListCouples;
use App\Filament\Resources\Couples\Pages\ViewCouple;
use App\Filament\Resources\Couples\Schemas\CoupleForm;
use App\Filament\Resources\Couples\Schemas\CoupleInfolist;
use App\Filament\Resources\Couples\Tables\CouplesTable;
use App\Models\Couple;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CoupleResource extends Resource
{
    protected static ?string $model = Couple::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    
    protected static ?string $navigationLabel = 'Parejas';
    
    protected static ?string $modelLabel = 'Pareja';
    
    protected static ?string $pluralModelLabel = 'Parejas';

    public static function form(Schema $schema): Schema
    {
        return CoupleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoupleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouplesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCouples::route('/'),
            'create' => CreateCouple::route('/create'),
            'view' => ViewCouple::route('/{record}'),
            'edit' => EditCouple::route('/{record}/edit'),
        ];
    }
}
