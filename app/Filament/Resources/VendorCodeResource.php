<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorCodeResource\Pages;
use App\Filament\Resources\VendorCodeResource\RelationManagers;
use App\Models\VendorCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorCodeResource extends Resource
{
    protected static ?string $model = VendorCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Артикулы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('vendor_code')
                    ->required()
                    ->prefix('L')
                    ->label('Артикул')
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Наименование')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('img')
                    ->label('Картинка')
                    ->image()
                    ->imageEditor(),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor_code')
                    ->label('Арт.')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->getStateUsing(function (Model $record) {
                        return 'L' . $record['vendor_code'];
                    }),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Картинка')
                    ->width(200)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->height('auto'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Название'),
                // Tables\Columns\SelectColumn::make('type')
                //     ->label('Тип профиля')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: false)
                //     ->options([
                //         '' => '',
                //         'aluminium' => 'Алюминий',
                //         'polycarbonate' => 'Поликарбонат'
                //     ]),
                
                Tables\Columns\SelectColumn::make('unit')
                    ->label('Ед. изм.')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->options([
                        'шт.' => 'шт.',
                        'м.п.' => 'м.п.',
                        'кг.' => 'кг.',
                        'см.' => 'см.',
                        'мм.' => 'мм.'
                    ]),
                Tables\Columns\TextInputColumn::make('price')
                    ->label('Цена')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->type('number')
                    ->rules(['required']),
                Tables\Columns\TextInputColumn::make('discount')
                    ->label('Скидка %')
                    ->type('number'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Остаток')
                    ->suffix(' шт.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListVendorCodes::route('/'),
            'create' => Pages\CreateVendorCode::route('/create'),
            'edit' => Pages\EditVendorCode::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
