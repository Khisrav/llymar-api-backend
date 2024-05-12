<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
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
                    ->label('Дата создания')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                //http://127.0.0.1:8000/admin/orders/2/edit
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('neutral')
                    ->url(function (Model $record) : string {
                        $id = $record->id;
                        return 'http://localhost:5173/generate-pdf/' . $record->user_id . '-' . $id;
                    })
                    ->openUrlInNewTab(),
                Action::make('edit')
                    ->label('Изменить')
                    ->icon('heroicon-m-pencil-square')
                    ->url(function (Model $record) : string {
                        $id = $record->id;
                        return env('APP_URL') . '/admin/orders/' . $id . '/edit';
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }
}
