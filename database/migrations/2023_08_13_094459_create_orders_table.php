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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            // client_phone
            $table->string('client_phone');
            // client_email
            $table->string('client_email');
            // client_message
            $table->string('client_message')->nullable();
            // cart_content
            $table->json('cart_content');
            // shipping_message
            $table->string('shipping_message')->nullable();
            // status
            $table->string('status_name')->default('pending');
            $table->string('status_color')->default('primary');

            $table->boolean('compleated')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
