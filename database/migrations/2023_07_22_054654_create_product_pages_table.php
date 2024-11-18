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
        Schema::create('product_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->json('title')->nullable()->setDefault('[]');
            $table->json('description')->nullable()->setDefault('[]');
            $table->json('media_gallery')->nullable()->setDefault('[]');
            $table->json('characteristic_icons')->nullable()->setDefault('[]');
            $table->json('documentations')->nullable()->setDefault('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_pages');
    }
};
