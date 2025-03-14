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
            $table->text('image')->nullable();
            $table->string('name');
            $table->decimal('price', total: 16, places: 2);
            $table->string('sku')->unique()->nullable();  // SKU is optional but useful
            $table->text('description')->nullable();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');

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
