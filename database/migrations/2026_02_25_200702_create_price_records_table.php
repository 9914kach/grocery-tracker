<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_product_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 8, 2);
            $table->string('currency', 3)->default('SEK');
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_records');
    }
};
