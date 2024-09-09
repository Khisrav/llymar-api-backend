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
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(50)
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('id')
                        ->numeric()
                        ->sortable()
                        ->prefix('ID: ')
                        ->weight(FontWeight::Bold)
                        ->grow(false)
                        // ->alignCenter()
                        ->toggleable(isToggledHiddenByDefault: false),
                    Tables\Columns\TextColumn::make('user.name')
                        ->searchable()
                        ->prefix('')
                        // ->alignCenter()
                        ->toggleable(isToggledHiddenByDefault: false),
                    Tables\Columns\TextColumn::make('comment')
                        ->prefix('Комментарий: ')
                        ->searchable()
                        ->listWithLineBreaks()
                        ->wrap()
                        // ->alignCenter()
                        ->toggleable(isToggledHiddenByDefault: false),
                    Stack::make([
                        Tables\Columns\SelectColumn::make('status')
                            ->searchable()
                            ->toggleable(isToggledHiddenByDefault: false)
                            ->options([
                                'pending' => 'В обработке',
                                'completed' => 'Завершен'
                            ])
                            // ->alignCenter()
                            ->label('Статус'),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime('d.M.Y - h:m')
                        ->sortable()
                        // ->alignCenter()
                        ->toggleable(isToggledHiddenByDefault: true),
                    ])->space(3)->grow(false),
                    Tables\Columns\TextColumn::make('total_price')
                        ->numeric()
                        ->toggleable(isToggledHiddenByDefault: false)
                        ->sortable()
                        ->money('RUB')
                        // ->alignCenter()
                        ->label('Итог'),
                ])->from('md'),

            ])
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
            ]);
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
