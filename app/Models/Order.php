<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'customer_name',
        'total_price',
        'notes',
        'status',
        'midtrans_order_id',
        'snap_token',
    ];

    /**
     * Mendapatkan meja yang terkait dengan pesanan.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Mendapatkan semua item dalam pesanan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Mendapatkan rating yang terkait dengan pesanan.
     */
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }
}
