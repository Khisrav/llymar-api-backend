<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Additional;
use App\Models\Item;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class AdditionalsRelationManager extends RelationManager
{
    protected static string $relationship = 'additionals';
    protected static ?string $title = 'Доп. товары';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->required()
                    ->options(Item::all()->mapWithKeys(function ($item) {
                        return [$item->vendor_code => 'L' . $item->vendor_code . ' - ' . $item->name];
                    }))
                    ->label('Артикул')
                    ->native(false),
                    
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->label('Кол-во'),
                
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Цена'),
                
                Forms\Components\TextInput::make('discount')
                    ->required()
                    // ->suffix('%')
                    ->label('Коэф. скидки')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                // Tables\Columns\TextColumn::make('order_id')
                //     ->label('ID заказа')
                //     ->sortable(),
                Tables\Columns\SelectColumn::make('item_id')
                    ->label('Артикул')
                    ->searchable()
                    ->options(Item::all()->mapWithKeys(function ($item) {
                        return [$item->vendor_code => 'L' . $item->vendor_code . ' - ' . $item->name];
                    })),
                Tables\Columns\TextInputColumn::make('amount')
                    ->label('Кол-во')
                    ->sortable()
                    ->type('number'),
                Tables\Columns\TextInputColumn::make('price')
                    ->label('Цена (₽)')
                    ->sortable()
                    ->type('number'),
                Tables\Columns\TextInputColumn::make('discount')
                    ->label('Коэф. скидки')
                    ->sortable()
                    ->type('number'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Итого')
                    ->state(function (Model $record) {
                        return intval($record->amount * $record->price * $record->discount) . '₽';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
