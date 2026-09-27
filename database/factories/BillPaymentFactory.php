<?php

namespace Database\Factories;

use App\Models\BillPayment;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillPaymentFactory extends Factory
{
    protected $model = BillPayment::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(200, 5000);
        $serviceFee = fake()->numberBetween(10, 200);

        return [
            'store_id' => Store::factory(),
            'customer_id' => Customer::factory(),
            'biller' => fake()->company(),
            'account_number' => fake()->numerify('#######'),
            'account_name' => fake()->name(),
            'amount' => $amount,
            'service_fee' => $serviceFee,
            'total' => $amount + $serviceFee,
            'reference_number' => fake()->unique()->numerify('BILL-######'),
            'status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
