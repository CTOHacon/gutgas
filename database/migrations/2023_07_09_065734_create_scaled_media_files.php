<?php

use App\Models\MediaFile;
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
        Schema::create('scaled_media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_file_id')->constrained(
                'media_files',
                'id'
            )->onDelete('cascade');
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('size')->nullable();
            $table->string('scale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scaled_media_files');
    }
};
