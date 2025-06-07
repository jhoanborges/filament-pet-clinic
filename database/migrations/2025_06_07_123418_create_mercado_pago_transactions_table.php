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
        Schema::create('mercado_pago_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('type')->nullable();
            $table->string('user_id')->nullable();
            $table->string('external_reference');
            $table->text('description')->nullable();
            $table->string('processing_mode')->nullable();
            $table->string('country_code')->nullable();
            $table->json('integration_data')->nullable();
            $table->string('status');
            $table->string('status_detail')->nullable();
            $table->json('config')->nullable();
            $table->json('transactions')->nullable();
            $table->json('taxes')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('external_reference');
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mercado_pago_transactions');
    }
};
