<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use App\Models\Additional;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OpeningsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\AdditionalsRelationManager;
use App\Filament\Resources\VendorAmountResource\RelationManagers\VendorAmountsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->label('Пользователь')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\SelectColumn::make('material_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
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
                    ->label('Статус'),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->money('RUB')
                    ->label('Итог'),
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
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(function (Model $record) : string {
                        $id = $record->id;
                        return 'http://localhost:5173/generate-pdf/' . $id;
                    })
                    ->openUrlInNewTab(),
                
                Tables\Actions\DeleteAction::make(),
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
