<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->string('unit_size')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
