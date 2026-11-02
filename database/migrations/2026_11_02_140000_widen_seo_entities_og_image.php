<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * og_image is a translatable JSON map (one path per locale); with six locales
     * it no longer fits in varchar(255).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE seo_entities MODIFY og_image TEXT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE seo_entities MODIFY og_image VARCHAR(255) NULL');
        }
    }
};
