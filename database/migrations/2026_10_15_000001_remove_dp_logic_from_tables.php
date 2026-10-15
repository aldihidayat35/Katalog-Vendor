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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'dp_price')) {
                $table->dropColumn('dp_price');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'dp_price')) {
                $table->dropColumn('dp_price');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_option')) {
                $table->dropColumn('payment_option');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('dp_price')->nullable()->after('discount_price');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedInteger('dp_price')->nullable()->after('unit_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_option', ['dp', 'full'])->default('full')->after('shipping_method');
        });
    }
};
