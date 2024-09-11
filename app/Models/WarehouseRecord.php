<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseRecord extends Model
{
    use HasFactory;
    
    public function vendor(): BelongsTo 
    {
        // return $this->belongsTo(VendorCode::class, 'vendor_code', 'vendor_code_id');
        return $this->belongsTo(VendorCode::class, 'vendor_code');
    }
    
    public function item(): BelongsTo 
    {
        // return $this->belongsTo(Item::class, 'vendor_code', 'vendor_code_id'); 
        return $this->belongsTo(Item::class, 'vendor_code'); 
    }
    
    protected static function boot()
    {
        parent::boot();

        // Trigger when a row is created or updated
        static::saved(function ($warehouse) {
            if ($warehouse->wasRecentlyCreated) {
                // If the row was newly created, just add the quantity
                $warehouse->adjustVendorAndItemQuantities($warehouse->quantity);
            } else {
                // If the row was updated, adjust based on the difference between old and new quantity
                $originalQuantity = $warehouse->getOriginal('quantity');
                $quantityDifference = $warehouse->quantity - $originalQuantity;
                $warehouse->adjustVendorAndItemQuantities($quantityDifference);
            }
        });

        // Trigger when a row is deleted
        static::deleted(function ($warehouse) {
            // Reverse the effect of the deleted row's quantity
            $warehouse->adjustVendorAndItemQuantities(-$warehouse->quantity);
        });
    }

    /**
     * Method to adjust the vendor and item quantities
     * 
     * @param float $quantityChange Quantity to adjust (can be positive or negative)
     */
    public function adjustVendorAndItemQuantities($quantityChange)
    {
        // Find the corresponding vendor using vendor_code_id
        $vendor = VendorCode::where('vendor_code', $this->vendor_code_id)->first();

        // Find the corresponding item using vendor_code_id
        $item = Item::where('vendor_code', $this->vendor_code_id)->first();

        // Update the vendor quantity, if found
        if ($vendor) {
            $vendor->quantity += $quantityChange;
            $vendor->save();

            Log::info('Vendor quantity adjusted: ' . $vendor->quantity);
        }

        // Update the item quantity, if found
        if ($item) {
            $item->quantity += $quantityChange;
            $item->save();

            Log::info('Item quantity adjusted: ' . $item->quantity);
        }
    }
}