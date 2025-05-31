<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('payment_method', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->float('fee_percent', 2)->nullable(false);
            $table->float('discount_percent', 2)->nullable(false);
            $table->integer('max_discount_installments')->nullable(false);
            $table->integer('max_installments')->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
