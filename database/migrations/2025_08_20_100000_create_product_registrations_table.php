<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city');
            $table->text('address')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number');
            $table->date('purchase_date');
            $table->unsignedInteger('price');
            $table->string('purchase_place');
            $table->boolean('subscription_consent')->default(false);
            $table->string('locale', 8)->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_registrations');
    }
};
