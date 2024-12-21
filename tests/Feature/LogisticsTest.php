<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Deliverynote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class LogisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_unfulfilled_orders_on_page()
    {
        // Auth
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        // Create unfulfilled order
        Order::factory()->create([ // Learnt factories from Laravel Docs - https://laravel.com/docs/11.x/eloquent-factories#instantiating-models - Adam
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

    public function test_create_delivery()
    {
        // Auth
        $user = User::where('email', 'Manager1@email.com')->first();
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

        $allFulfilled = true;
        foreach ($order->items as $orderItem) {
            $deliveredQuantity = DeliveryNote::where('delivery_note.order_id', '=', $orderItem->order_id)
                ->join('delivered_item', 'delivered_item.delivery_note_id', '=', 'delivery_note.id')
                ->where('delivered_item.item_id', $orderItem->item_id)
                ->sum('delivered_item.quantity');

            if ($deliveredQuantity < $orderItem->ordered) {
                $allFulfilled = false;
            }
            ;
        }

        if ($allFulfilled) {
            $order->update(['fulfilled' => 1]);
        }
        
        // Response
        $order->refresh();
        $this->assertEquals(1, $order->fulfilled);
        $response = $this->get(route('logistics.showdeliverynotes', ['order' => $order->id]));
        $response->assertSuccessful();
    }
}