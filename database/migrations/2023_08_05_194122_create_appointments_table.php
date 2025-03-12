<?php

use App\Models\Slot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->foreignId('pet_id')
                ->constrained('pets')
                ->cascadeOnDelete();
            $table->string('status')->default('created');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->string('order_column')->nullable();
            $table->boolean('sort_when_creating')->default(false);
            $table->string('slot_id')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
