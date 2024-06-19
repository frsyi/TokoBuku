<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;


    //ini kalo pake API tidak dicommand
    // protected $casts = [
    //     'is_complete' => 'boolean',
    // ];


    //ini kalo mau masukin book di blade tidak boleh dicommand
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publication_year',
        'price',
        'description',
        'image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
