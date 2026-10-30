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
        if (Schema::hasColumn('orders', 'shipping_method')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('client_surname')->nullable()->after('client_name');
            // np_department | np_postal_machine | np_courier | other
            $table->string('shipping_method')->nullable()->after('shipping_message');
            $table->string('shipping_city')->nullable();
            $table->string('shipping_city_ref')->nullable();
            $table->string('shipping_area')->nullable();
            $table->string('shipping_warehouse')->nullable();
            $table->string('shipping_warehouse_ref')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('locale', 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'client_surname',
                'shipping_method',
                'shipping_city',
                'shipping_city_ref',
                'shipping_area',
                'shipping_warehouse',
                'shipping_warehouse_ref',
                'shipping_address',
                'locale',
            ]);
        });
    }
};
