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
        Schema::create('product_filter_values', function (Blueprint $table) {
            $table->id();
            $table->json('value')->nullable();
            $table->unsignedBigInteger('product_filter_id')->index();
            $table->foreign('product_filter_id')->references('id')->on('product_filters')->onDelete('cascade');
            $table->unsignedBigInteger('sequence')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_filter_values');
    }
};
