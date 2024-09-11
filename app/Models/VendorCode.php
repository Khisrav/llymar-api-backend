<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorCode extends Model
{
    use HasFactory;
    
    public function warehouse_records(): HasMany
    {
        return $this->hasMany(WarehouseRecord::class);
    }
}
