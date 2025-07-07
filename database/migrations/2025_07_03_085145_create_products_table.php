<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique(); // PR00001 format
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->foreignId('category_id')->constrained()->default(1); // 1 = "Other"
            $table->foreignId('brand_id')->constrained()->default(1); // 1 = "Other"
            $table->foreignId('vendor_id')->constrained()->default(1); // 1 = "Other"
            $table->decimal('unit_price', 10, 2);
            $table->softDeletes();
            $table->timestamps();        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
