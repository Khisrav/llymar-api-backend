<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use function Livewire\before;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Field;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OpeningsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\AdditionalsRelationManager;
use App\Filament\Resources\VendorAmountResource\RelationManagers\VendorAmountsRelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Заказы';
    protected static ?string $title = 'Заказы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->native(false)
                    ->label('ID клиента'),
                Forms\Components\Select::make('material_type')
                    ->required()
                    ->label('Тип материала')
                    ->options([
                        'aluminium' => 'Алюминий',
                        'polycarbonate' => 'Поликарбонат'
                    ])
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Статус')
                    ->options([
                        'pending' => 'В обработке',
                        'completed' => 'Завершен'
                    ])
                    ->native(false),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('₽')
                    ->label('Итого'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('user.name')
                    // ->numeric()
                    // ->sortable()
                    ->searchable()
                    ->wrap()
                    ->label('Пользователь')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\SelectColumn::make('material_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->options([
                        'aluminium' => 'Алюминий',
                        'polycarbonate' => 'Поликарбонат'
                    ])
                    ->label('Материал профиля'),
                Tables\Columns\SelectColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->options([
                        'pending' => 'В обработке',
                        'completed' => 'Завершен'
                    ])
                    ->selectablePlaceholder(false)
                    ->label('Статус')
                    ->afterStateUpdated(function (?string $state, Model $record) {
                        // Fetch vendor and item orders once to avoid repeated queries
                        $vendorOrders = DB::table('vendor_amounts')
                            ->where('order_id', $record->id)
                            ->get();
                    
                        $itemOrders = DB::table('additionals')
                            ->where('order_id', $record->id)
                            ->get();
                    
                        // Define the operation based on the order status
                        $operation = $state === 'completed' ? 'decrement' : 'increment';
                    
                        // Update vendor_codes quantities
                        foreach ($vendorOrders as $vendorOrder) {
                            DB::table('vendor_codes')
                                ->where('vendor_code', $vendorOrder->vendor_code_id)
                                ->{$operation}('quantity', $vendorOrder->amount);
                        }
                    
                        // Update items quantities
                        foreach ($itemOrders as $itemOrder) {
                            DB::table('items')
                                ->where('vendor_code', $itemOrder->item_id)
                                ->{$operation}('quantity', $itemOrder->amount);
                        }
                    }),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Комментарий')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('delivery')
                    ->label('Доставка')
                    ->type('number'),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->money('RUB')
                    ->label('Итог'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(function (Model $record) : string {
                        $id = $record->id;
                        return "https://llymar.ru/generate-pdf/" . $record->user_id . '-' . $id;
                    })
                    ->openUrlInNewTab(),
                
                Tables\Actions\DeleteAction::make()
                    ->before(function (Model $record) {
                        DB::table('vendor_amounts')->where('order_id', '=', $record->id)->delete();
                        DB::table('openings')->where('order_id', '=', $record->id)->delete();
                        DB::table('additionals')->where('order_id', '=', $record->id)->delete();
                    }),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Model $record) {
                            DB::table('vendor_amounts')->where('order_id', '=', $record->id)->delete();
                            DB::table('openings')->where('order_id', '=', $record->id)->delete();
                            DB::table('additionals')->where('order_id', '=', $record->id)->delete();
                        })
                ]),
            ])
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(50);
    }

    public static function getRelations(): array
    {
        return [
            AdditionalsRelationManager::class,
            OpeningsRelationManager::class,
            VendorAmountsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
