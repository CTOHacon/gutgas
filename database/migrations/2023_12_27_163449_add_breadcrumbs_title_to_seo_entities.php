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
        // The create_seo_entities_table migration already defines this column,
        // so guard against re-adding it when migrating a fresh database.
        if (Schema::hasColumn('seo_entities', 'breadcrumbs_title')) {
            return;
        }

        Schema::table('seo_entities', function (Blueprint $table) {
            $table->json('breadcrumbs_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('seo_entities', 'breadcrumbs_title')) {
            return;
        }

        Schema::table('seo_entities', function (Blueprint $table) {
            $table->dropColumn('breadcrumbs_title');
        });
    }
};
