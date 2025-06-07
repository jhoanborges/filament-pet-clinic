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
        Schema::create('mercadopago_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notification_id')->nullable();
            $table->string('type'); // payment, plan, subscription, etc.
            $table->boolean('live_mode')->default(false);
            $table->string('action'); // payment.created, payment.updated, etc.
            $table->string('api_version')->nullable();
            $table->string('user_id')->nullable(); // MercadoPago user ID
            $table->string('resource_id'); // payment ID, subscription ID, etc.
            $table->string('status')->nullable(); // approved, pending, rejected, etc.
            $table->json('data')->nullable(); // Full notification payload
            $table->timestamps();

            // Indexes for faster lookups
            $table->index('notification_id');
            $table->index('resource_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mercadopago_notifications');
    }
};
