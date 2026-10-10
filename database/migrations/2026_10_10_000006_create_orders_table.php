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
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('instagram_account')->nullable();
            $table->date('event_date')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_method');
            $table->enum('payment_option', ['dp', 'full']);
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
            $table->string('promo_code')->nullable();
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('total_price');
            $table->text('notes')->nullable();
            $table->enum('status', ['lead', 'contacted', 'confirmed', 'completed', 'cancelled'])->default('lead');
            $table->text('whatsapp_message');
            $table->timestamps();

            $table->index('order_number');
            $table->index('status');
            $table->index('user_id');
            $table->index('created_at');
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
