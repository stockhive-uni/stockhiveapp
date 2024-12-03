<?php
namespace Database\Factories;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory {
    protected $model = Order::class;
    public function definition() {
        return [
            'user_id' => 1,
            'store_id' => 1,
            'date_time' => now(),
            'fulfilled' => 0,
        ];
    }
}