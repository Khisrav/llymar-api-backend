<?php

namespace App\Models;

use App\Models\Additional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'material_type',
        'status',
        'total_price',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function additionals(): HasMany {
        return $this->hasMany(Additional::class);
    }

    public function openings(): HasMany {
        return $this->hasMany(Opening::class);
    }

    public function vendorAmounts(): HasMany {
        return $this->hasMany(VendorAmount::class);
    }
}
