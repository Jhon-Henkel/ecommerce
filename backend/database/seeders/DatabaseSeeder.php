<?php

namespace Database\Seeders;

use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        Product::create(['name' => 'IPhone 16 240 GB', 'price' => 5750.50]);
        Product::create(['name' => "Ar Condicionado Split 12000 Btu's", 'price' => 3700]);
        Product::create(['name' => 'Parafusadeira 21v', 'price' => 250]);
        Product::create(['name' => 'Playstation 5', 'price' => 4300]);
        Product::create(['name' => 'Guitarra Elétrica Gibson', 'price' => 15000]);

        PaymentMethod::create(['name' => 'Pix', 'discount_percent' => 10, 'fee_percent' => 0, 'max_discount_installments' => 1, 'max_installments' => 1]);
        PaymentMethod::create(['name' => 'Cartão de Crédito', 'discount_percent' => 10, 'fee_percent' => 1, 'max_discount_installments' => 1, 'max_installments' => 12]);
    }
}
