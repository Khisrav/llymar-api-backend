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
use Filament\Tables\Columns\ImageColumn;

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
                            $vendors = VendorCode::where('is_warehouse', true)->get()->mapWithKeys(function($vendor) {
                                return [$vendor->vendor_code => 'L' . $vendor->vendor_code . ' - ' . $vendor->name];
                            });

                            $items = Item::where('is_warehouse', true)->get()->mapWithKeys(function($item) {
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('vendor_code_id')
                    ->label('Арт.')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return 'L' . $record['vendor_code_id'];
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('vendor.img')
                    ->label('Изображение')
                    ->width(160)
                    ->height('auto')
                    ->getStateUsing(function (Model $record) {
                        $vendor = VendorCode::where('vendor_code', $record->vendor_code_id)->first(); // Get related vendor
                        
                        // dd($vendor->img);
                        return $vendor->img; // Return the image filename if available
                    }),
                TextColumn::make('vendor_code_name')
                    ->label('Наименование')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->getStateUsing(function (Model $record) {
                        $vendor = VendorCode::where('vendor_code', $record['vendor_code_id'])->first();
                        $item = Item::where('vendor_code', $record['vendor_code_id'])->first();

                        if ($vendor) {
                            return $vendor->name;
                        } else {
                            return $item->name;
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                TextInputColumn::make('quantity')
                    ->label('Количество')
                    ->searchable()
                    ->sortable()
                    ->type('number')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextInputColumn::make('purchase_price')
                    ->label('Закупочная цена')
                    ->sortable()
                    ->type('number')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('vendor.price')
                    ->label('Розничная цена')
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        $vendor = VendorCode::where('vendor_code', $record['vendor_code_id'])->first();
                        return $vendor->price;
                    })
                    ->alignCenter()
                    ->money('RUB')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('id', 'desc')
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
            ])
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(50);
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
