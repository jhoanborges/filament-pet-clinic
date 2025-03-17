<?php

use App\Models\Clinic;
use App\Models\InventoryLog;
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
        Schema::create('clinic_inventory_log', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Clinic::class);
            $table->foreignIdFor(InventoryLog::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_inventory_log');
    }
};
