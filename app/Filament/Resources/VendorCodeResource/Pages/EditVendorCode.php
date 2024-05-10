<?php

namespace App\Filament\Resources\VendorCodeResource\Pages;

use App\Filament\Resources\VendorCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorCode extends EditRecord
{
    protected static string $resource = VendorCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
