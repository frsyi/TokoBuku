<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_title',
        'amount',
        'unit_price',
        'total_price',
        'status',
        'payment_proof',
        'tracking_number',
        'order_status',
        'confirmation',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order_status' => 'boolean',
        'confirmation' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
