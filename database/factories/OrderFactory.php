<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 100, 5000);
        $taxes = $subtotal * 0.16; // 16% tax rate
        $discount = $this->faker->randomFloat(2, 0, $subtotal * 0.2); // Up to 20% discount
        $total = $subtotal + $taxes - $discount;

        return [
            'reference' => 'ORD-' . $this->faker->unique()->numerify('#####'),
            'subtotal' => $subtotal,
            'taxes' => $taxes,
            'discount' => $discount,
            'total' => $total,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'bank_transfer', 'cash']),
            'currency' => 'MXN',
            'notes' => $this->faker->optional(0.7)->text(200),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): Factory
    {

        return $this->afterCreating(function (Order $order) {
            // Get random products
            $products = Product::inRandomOrder()->take(rand(1, 5))->get();
            $year = date('Y');

            // Attach products with quantity and price
            foreach ($products as $product) {
                $quantity = rand(1, 10);
                $price = $product->price;

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'sku' => $product->sku,
                    'created_at' => $this->faker->dateTimeBetween("$year-01-01", "$year-12-31"),
                    'updated_at' => $this->faker->dateTimeBetween("$year-01-01", "$year-12-31"),
                ]);
            }

            // Recalculate order totals
            $subtotal = 0;
            foreach ($order->products as $product) {
                $subtotal += $product->pivot->price * $product->pivot->quantity;
            }

            $taxes = $subtotal * 0.16;
            $discount = $order->discount;
            $total = $subtotal + $taxes - $discount;

            $order->update([
                'subtotal' => $subtotal,
                'taxes' => $taxes,
                'total' => $total,
            ]);
        });
    }
}
