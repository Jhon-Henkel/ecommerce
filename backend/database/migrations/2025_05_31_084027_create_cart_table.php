<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(false)->constrained('users');
            $table->foreignId('payment_method_id')->nullable()->default(null)->constrained('payment_method');
            $table->integer('total_items')->nullable(false)->default(0);
            $table->integer('installments')->nullable(false);
            $table->float('subtotal_amount')->nullable(false)->default(0);
            $table->float('discount_amount')->nullable(false)->default(0);
            $table->float('total_amount', 2)->nullable(false)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
