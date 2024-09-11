<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?int $navigationSort = 4;

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
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->label('Ед. изм.')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Цена')
                    ->numeric()
                    ->prefix('₽'),
                Forms\Components\TextInput::make('discount')
                    ->label('Скидка %')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Forms\Components\TextInput::make('vendor_code')
                    ->required()
                    ->prefix('L')
                    ->label('Артикул')
                    ->numeric(),
                Forms\Components\FileUpload::make('img')
                    ->label('Картинка')
                    ->image()
                    ->imageEditor(),
            ]);
    }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             Split::make([
    //                 Tables\Columns\ImageColumn::make('img')
    //                     ->label('Картинка')
    //                     ->width(160)
    //                     ->height('auto'),
    //                 Stack::make([
    //                     Tables\Columns\TextColumn::make('vendor_code')
    //                         ->sortable()
    //                         ->searchable()
    //                         ->label('Артикул')
    //                         ->prefix('Артикул: L')
    //                         ->weight(FontWeight::Bold),
    //                     Tables\Columns\TextColumn::make('name')
    //                         ->searchable()
    //                         ->label('Наименование')
    //                         ->listWithLineBreaks()
    //                         ->wrap(),
    //                 ]),
    //                 Stack::make([
    //                     Split::make([
    //                         Tables\Columns\TextColumn::make('unit')
    //                             ->prefix('Ед изм: '),
    //                         Tables\Columns\TextColumn::make('price')
    //                             ->sortable()
    //                             ->prefix('Цена: ')
    //                             ->suffix(' руб.'),
    //                     ]),
    //                     Tables\Columns\TextColumn::make('discount')
    //                         ->prefix('Скидка: ')
    //                         ->suffix('%'),
    //                 ])->space(3),
    //             ])->from('md')
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\DeleteAction::make(),
    //             // Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('img')
                    ->label('Картинка')
                    ->width(200)
                    ->height('auto')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->label('Наименование')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Ед. изм.')
                    ->html()
                    ->getStateUsing(fn ($record): string => $record->unit == 'м2' ? 'м<sup>2</sup>' : $record->unit)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('price')
                    ->sortable()
                    ->type('number')
                    ->label('Цена')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('discount')
                    ->label('Скидка %')
                    ->type('number')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('vendor_code')
                    ->type('number')
                    ->sortable()
                    ->searchable()
                    ->label('Артикул')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Остаток')
                    ->searchable()
                    ->suffix(' шт.')
                    ->toggleable(isToggledHiddenByDefault: false),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
