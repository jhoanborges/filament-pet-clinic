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
        Schema::create('billing_information', function (Blueprint $table) {
            $table->id();
            $table->string('rfc')->unique();
            $table->string('razon_social')->unique();
            $table->string('regimen_fiscal');
            $table->string('postal_code')->nullable();
            $table->string('street_address')->nullable();
            $table->string('numero_interior')->nullable();
            $table->string('numero_exterior')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('billing_information_id')->nullable()->constrained('billing_information')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_information');

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['billing_information_id']);
            $table->dropColumn('billing_information_id');
        });
    }
};
