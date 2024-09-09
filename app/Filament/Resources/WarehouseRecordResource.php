<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\VendorCode;
use Filament\Tables\Table;
use App\Models\WarehouseRecord;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WarehouseRecordResource\Pages;
use App\Filament\Resources\WarehouseRecordResource\RelationManagers;

class WarehouseRecordResource extends Resource
{
    protected static ?string $model = WarehouseRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Записи склада';
    
    public static function getTableQuery()
    {
        return parent::getTableQuery()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('quantity')
                    ->label('Количество')
                    ->required()
                    ->numeric(),
                Select::make('vendor_code_id')
                    ->label('Артикул')
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->options(
                        function(): array {
                            $vendors = VendorCode::all()->mapWithKeys(function($vendor) {
                                return [$vendor->vendor_code => 'L' . $vendor->vendor_code . ' - ' . $vendor->name];
                            });

                            $items = Item::all()->mapWithKeys(function($item) {
                                return [$item->vendor_code => 'L' . $item->vendor_code . ' - ' . $item->name];
                            });
                            
                            return [
                                'Артикулы' => $vendors->toArray(),
                                'Доп. товары' => $items->toArray(),
                            ];
                        }
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    -> label('ID')
                    ->sortable(),
                TextColumn::make('vendor_code_id')
                    ->label('Арт.')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return 'L' . $record['vendor_code_id'];
                    }),
                TextColumn::make('vendor_code_name')
                    ->label('Наименование')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        $vendor = VendorCode::all()->where('vendor_code', $record['vendor_code_id'])->first();
                        $item = Item::all()->where('vendor_code', $record['vendor_code_id'])->first();

                        if ($vendor) {
                            return $vendor->name;
                        } else {
                            return $item->name;
                        }
                    }),
                TextInputColumn::make('quantity')
                    ->label('Количество')
                    ->searchable()
                    ->sortable()
                    ->type('number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListWarehouseRecords::route('/'),
            'create' => Pages\CreateWarehouseRecord::route('/create'),
            'edit' => Pages\EditWarehouseRecord::route('/{record}/edit'),
        ];
    }
}
