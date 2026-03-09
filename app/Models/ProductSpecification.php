<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $guarded = []; // Tüm alanlara yazmaya izin ver

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'unit',
        'group',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
