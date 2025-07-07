<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'barcode',
        'category_id',
        'brand_id',
        'vendor_id',
        'unit_price'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latest = Product::withTrashed()->latest('id')->first();
            $nextId = $latest ? $latest->id + 1 : 1;
            $product->product_id = 'PR' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    
}
