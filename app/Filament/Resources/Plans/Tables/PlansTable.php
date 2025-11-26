<?php

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('couple.id')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('overall_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fun_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emotional_connection_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('organization_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('value_for_money_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ratings_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('photos_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_rated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
