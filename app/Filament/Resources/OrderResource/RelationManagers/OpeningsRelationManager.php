<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Opening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OpeningsRelationManager extends RelationManager
{
    protected static string $relationship = 'openings';
    protected static ?string $title = 'Проемы';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->label('ID заказа')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('Тип проема')
                    ->native(false)
                    ->options([
                        'left' => 'Левый проем',
                        'right' => 'Правый проем',
                        'center' => 'Центральный проем',
                    ]),
                Forms\Components\TextInput::make('doors')
                    ->required()
                    ->label('Кол-во створок')
                    ->maxLength(255),
                Forms\Components\TextInput::make('width')
                    ->required()
                    ->label('Ширина (мм)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('height')
                    ->required()
                    ->label('Высота (мм)')
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
                Tables\Columns\SelectColumn::make('type')
                    ->label('Тип проема')
                    ->searchable()
                    // ->options(Opening::all()->pluck('name', 'type')),
                    ->options([
                        'left' => 'Левый проем',
                        'right' => 'Правый проем',
                        'center' => 'Центральный проем',
                    ]),
                Tables\Columns\TextInputColumn::make('doors')
                    ->label('Створки')
                    ->sortable()
                    ->type('number'),
                Tables\Columns\TextInputColumn::make('height')
                    ->label('Высота (мм)')
                    ->sortable()
                    ->type('number'),
                Tables\Columns\TextInputColumn::make('width')
                    ->label('Ширина (мм)')
                    ->sortable()
                    ->type('number'),
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
