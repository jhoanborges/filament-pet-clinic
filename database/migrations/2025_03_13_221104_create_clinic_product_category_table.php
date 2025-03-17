<?php

use App\Models\Clinic;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_product_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clinic::class);
            $table->foreignIdFor(ProductCategory::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_product_category');
    }
};
