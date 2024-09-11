<?php

namespace App\Filament\Resources\WarehouseRecordResource\Pages;

use App\Filament\Resources\WarehouseRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseRecord extends EditRecord
{
    protected static string $resource = WarehouseRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected static ?string $title = 'Редактировать запись';
}
