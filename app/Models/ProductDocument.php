<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    use HasFactory;
    protected $guarded = []; // Tüm alanlara yazmaya izin ver

    protected $fillable = [
        'product_id',
        'title',
        'file_path',
        'type',
        'language_code',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
