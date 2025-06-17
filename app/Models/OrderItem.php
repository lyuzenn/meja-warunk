<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// PERBAIKAN: Menggunakan nama alias untuk menghindari konflik
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToRelation;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price_at_order',
    ];

    /**
     * Mendapatkan menu yang terkait dengan item pesanan ini.
     * PERBAIKAN: Menggunakan nama alias pada return type hint.
     */
    public function menu(): BelongsToRelation
    {
        return $this->belongsTo(Menu::class);
    }
}
