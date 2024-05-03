<?php

namespace App\Filament\Resources\VendorAmountResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\VendorCode;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VendorAmountsRelationManager extends RelationManager
{
    protected static string $relationship = 'vendorAmounts';
    protected static ?string $title = 'Артикулы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                // Tables\Columns\TextColumn::make('order_id')
                //     ->label('ID заказа'),
                Tables\Columns\SelectColumn::make('vendor_code_id')
                ->label('Артикул')
                ->sortable()
                ->options(VendorCode::all()->mapWithKeys(function ($vc) {
                    return [$vc->vendor_code => 'L' . $vc->vendor_code . ' - ' . $vc->name];
                })),
                Tables\Columns\TextInputColumn::make('amount')
                ->label('Кол-во')
                ->type('number')
                ->sortable(),
                Tables\Columns\TextInputColumn::make('price')
                ->label('Цена')
                ->type('number')
                ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                ->label('Итого')
                ->sortable()
                ->state(function (Model $record) {
                    return $record->amount * $record->price . '₽';
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
