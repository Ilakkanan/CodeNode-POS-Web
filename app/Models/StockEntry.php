<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 
        'vendor_id', 
        'quantity', 
        'unit_cost', 
        'total_cost',
        'entry_date',
        'reference_number',
        'notes',
        'user_id'
    ];

    protected $dates = [
        'entry_date',
        'created_at', 
        'updated_at',
        'deleted_at'
    ];
    
    // Or for Laravel 8+:
    protected $casts = [
        'entry_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}