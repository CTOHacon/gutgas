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
        Schema::create('media_filables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->unsignedBigInteger('media_filable_id');
            $table->string('media_filable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_filables');
    }
};
