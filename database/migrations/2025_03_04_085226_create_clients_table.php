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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('gender');
            $table->date('birthday')->nullable();
            $table->string('street_address')->nullable();
            $table->string('colony')->nullable(); // Neighborhood
            $table->string('city')->nullable();
            $table->string('municipality')->nullable();
            $table->string('postal_code')->nullable();
            //$table->boolean('allow_sms_notification')->default(false);
            //$table->boolean('allow_whatsapp_notification')->default(false);
            $table->boolean('allow_email_notification')->default(false);
          
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
