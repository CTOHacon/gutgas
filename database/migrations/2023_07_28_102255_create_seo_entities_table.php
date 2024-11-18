<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_entities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->nullable()->index();
            $table->morphs('seo_entiteable');
            $table->string('controller')->nullable();
            $table->string('action')->nullable();
            $table->json('title')->nullable();
            $table->json('breadcrumbs_title')->nullable();
            $table->json('description')->nullable();
            $table->string('og_image')->nullable();
            $table->json('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_entities');
    }
};
