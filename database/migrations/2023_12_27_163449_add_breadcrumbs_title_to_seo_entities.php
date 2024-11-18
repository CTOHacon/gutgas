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
        Schema::table('seo_entities', function (Blueprint $table) {
            $table->json('breadcrumbs_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_entities', function (Blueprint $table) {
            $table->dropColumn('breadcrumbs_title');
        });
    }
};
