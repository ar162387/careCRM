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
        Schema::create('demodulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_collection_setup_id');
            $table->string('filter_type');
            $table->string('filter_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demodulations');
    }
};
