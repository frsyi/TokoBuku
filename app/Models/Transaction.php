<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',
        'total_price',
        'payment_proof',
        'tracking_number',
        'order_status',
        'confirmation',
    ];

    protected $casts = [
        'tracking_number',
        'order_status' => 'boolean',
        'confirmation' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
