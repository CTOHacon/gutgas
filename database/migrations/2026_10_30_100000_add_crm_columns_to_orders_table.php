<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'crm_order_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            // SalesDrive order id, set once the order was pushed to the CRM
            $table->unsignedBigInteger('crm_order_id')->nullable();
            $table->timestamp('crm_sent_at')->nullable();
            $table->text('crm_error')->nullable();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('orders', 'crm_order_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['crm_order_id', 'crm_sent_at', 'crm_error']);
        });
    }
};
