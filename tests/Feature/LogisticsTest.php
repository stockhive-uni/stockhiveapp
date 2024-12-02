<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogisticsTest extends TestCase {
    use RefreshDatabase;
    
    public function test_display_unfulfilled_orders_on_page() {
        // Auth
        $user = User::where('email', 'test@email.com')->first();
        $this->actingAs($user);
        // Create unfulfilled order
        Order::factory()->create([
            'user_id' => $user->id,
            'store_id' => 1,
            'date_time' => now(),
            'fulfilled' => 0,
        ]);
        // Response
        $response = $this->get(route('logistics'));
        $response->assertStatus(200);
        $response->assertViewIs('logistics.index');
        $response->assertViewHas('orders');
    }
}