<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

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

    public function test_create_delivery() {
        // Auth
        $user = User::where('email', 'test@email.com')->first();
        $this->actingAs($user);
        // Factory
        $order = Order::factory()->create([ // Unfulfilled order
            'user_id' => $user->id,
            'store_id' => 1,
            'date_time' => now(),
            'fulfilled' => 0,
        ]);
        // Get items
        $items = DB::table('item')
            ->whereIn('id', [1, 2])
            ->get();
        // Write delivery note
        $note = DB::table('delivery_note')->insertGetId([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'date_time' => now(),
        ]);
        // Delivered items
        foreach ($items as $item) {
            $quantity = $item->id == 1 ? 3 : 5;
            // Add order item
            DB::table('order_item')->insert([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'ordered' => $quantity,
                'price' => $item->price
            ]);
            // Deliver item
            DB::table('delivered_item')->insert([
                'delivery_note_id' => $note,
                'item_id' => $item->id,
                'quantity' => $quantity,
            ]);
        }
        // Check if fulfilled
        $items = $order->items->map(function ($orderItem) use ($order) {
            $deliveredQuantity = $order->deliveryNotes->flatMap(function ($note) use ($orderItem) {
                return $note->deliveredItems->where('item_id', $orderItem->item_id);
            })->sum('quantity');
    
            $quantityLeft = $orderItem->ordered - $deliveredQuantity;
    
            return [
                'quantity_left' => $quantityLeft,
            ];
        });
    
        $allFulfilled = $items->every(function ($item) {
            return $item['quantity_left'] <= 0;
        });
    
        if ($allFulfilled) {
            $order->update(['fulfilled' => 1]);
        }
        // Response
        $order->refresh();
        $this->assertEquals(1, $order->fulfilled);
        $response = $this->get(route('logistics.show', $order->id));
        $response->assertSuccessful();
    }

    public function test_view_delivery() {
        return false; // Placeholder
    }
}