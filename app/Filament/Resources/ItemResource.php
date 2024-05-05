<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Доп. товары';
    protected ?string $title = 'Доп. товары';
    protected ?string $heading = 'Доп. товары';
    protected ?string $subheading = 'Доп. товары';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Наименование')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('img')
                //     ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->label('Ед. изм.')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Цена')
                    ->numeric()
                    ->prefix('₽'),
                Forms\Components\TextInput::make('vendor_code')
                    ->required()
                    ->prefix('L')
                    ->label('Артикул')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('name')
                    ->searchable()
                    ->label('Наименование'),
                // Tables\Columns\TextColumn::make('img')
                //     ->searchable(),
                Tables\Columns\TextInputColumn::make('unit')
                    ->label('Ед. изм.'),
                Tables\Columns\TextInputColumn::make('price')
                    ->sortable()
                    ->type('number')
                    ->label('Цена'),
                Tables\Columns\TextInputColumn::make('vendor_code')
                    ->type('number')
                    ->sortable()
                    ->searchable()
                    ->label('Артикул'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
