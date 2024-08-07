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
        Schema::create('service_representatives', function (Blueprint $table) {
            $table->id();
            $table->string('service_rep_name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->string('contact_name');
            $table->string('contact_title');
            $table->string('phone_number');
            $table->string('alt_phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('email');
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_representatives');
    }
};
