<?php

namespace Database\Seeders;

use App\Models\Promocode;
use Illuminate\Database\Seeder;

class PromocodeSeeder extends Seeder
{
    public function run()
    {
        $promocodes = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 5000,
                'usage_limit' => 100,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER2024',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 15000,
                'usage_limit' => 50,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'FIXED500',
                'type' => 'fixed',
                'value' => 500,
                'min_order_amount' => 3000,
                'usage_limit' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'NEWUSER',
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 10000,
                'usage_limit' => 200,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
        ];

        foreach ($promocodes as $promocode) {
            Promocode::create($promocode);
        }

        $this->command->info('Тестовые промокоды созданы!');
        $this->command->info('WELCOME10 - 10% скидка от 5000₽');
        $this->command->info('SUMMER2024 - 15% скидка от 15000₽');
        $this->command->info('FIXED500 - 500₽ скидка от 3000₽');
        $this->command->info('NEWUSER - 20% скидка от 10000₽');
    }
}