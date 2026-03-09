<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;

class Brand extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // SİHİRLİ METOT: Model oluşturulurken (creating) devreye girer
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            // Eğer slug boş geldiyse, isminden üret
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }


        


}
