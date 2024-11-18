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
        Schema::create('products_groups', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('icon')->nullable();
            $table->string('icon_hover')->nullable();
            $table->string('icon_active')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_groups');
    }
};
