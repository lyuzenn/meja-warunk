<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'rating_value',
        'comment',
    ];

    /**
     * Mendapatkan pesanan yang terkait dengan rating ini.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
