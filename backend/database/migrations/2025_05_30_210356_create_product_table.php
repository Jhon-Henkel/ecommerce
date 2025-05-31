<?php

use App\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->float('price', 2)->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Product::create(['name' => 'IPhone 16 240 GB', 'price' => 5750.50]);
        Product::create(['name' => "Ar Condicionado Split 12000 Btu's", 'price' => 3700]);
        Product::create(['name' => 'Parafusadeira 21v', 'price' => 250]);
        Product::create(['name' => 'Playstation 5', 'price' => 4300]);
        Product::create(['name' => 'Varal de chão Mor', 'price' => 99]);
        Product::create(['name' => 'Sofá', 'price' => 5000]);
        Product::create(['name' => 'Guitarra Elétrica Gibson', 'price' => 15000]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
