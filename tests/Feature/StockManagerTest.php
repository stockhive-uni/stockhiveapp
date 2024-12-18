<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagerTest extends TestCase {
    use RefreshDatabase;

    // Log in with a user with stock management perms (with an already created user)
    public function test_login_as_stock_manager() {
        $response = $this->post('/login', [
            'email' => 'Manager1@email.com',
            'password' => '123'
        ]);
        // Check that the user was redirected to the dashboard, meaning they are logged in
        $response->assertRedirect('/dashboard');
    }

    // Test that the stock manager can select items and order.
    public function test_select_items_to_order() {
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        // Get items from the database
        $items = Item::whereIn('id', [1, 2, 3])->get();
        $itemID = $items->pluck('id')->toArray();
        // Send request
        $response = $this->post(route('stock-management.chosenItems'), [
            'Order' => 'Order', // Order action
            'items' => $itemID, // Item IDs to be processed
        ]);
        // Get response
        $response->assertStatus(200);
        $response->assertViewIs('StockManager.order');
        $response->assertViewHas('items');
    }
    
    // Test that the stock manager can generate a report on multiple items.
    public function test_generate_report_multiple_items() {
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $items = Item::whereIn('id', [1, 2, 3])->get();
        $itemID = $items->pluck('id')->toArray();
        $response = $this->post(route('stock-management.chosenItems'), [
            'Report' => 'Report', // Send to report page
            'items' => $itemID, 
        ]);

        // Get response
        $response->assertStatus(200);
        $response->assertViewIs('StockManager.report');
        // Make sure all the data is returned in the report.
        $response->assertViewHas('allresults', function ($allresults) use ($itemID) {
            foreach($itemID as $id) {
                $foundItem = false;
                foreach($allresults as $result) { 
                    foreach ($result['data'] as $month => $data) {
                        if (isset($data['total'])) {
                            $foundItem = true;
                            break 2;  // Break out of both loops - https://www.php.net/manual/en/control-structures.break.php - Adam
                        }
                    }
                }
                if (!$foundItem) {
                    return false;
                }
            }
            return true;
        });
    }

    // Generate a test report on a single item.
    public function test_generate_report_singular_item() {
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $items = Item::whereIn('id', [1, 2, 3])->get();
        $itemID = $items->pluck('id')->toArray();
        $response = $this->post(route('stock-management.chosenItems'), [
            'Report' => 'Report',
            'items' => $itemID, 
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('StockManager.report');
        $response->assertViewHas('allresults', function ($allresults) use ($itemID) {
            foreach($allresults as $result) {
                foreach ($result['data'] as $month => $data) {
                    if (isset($data['total']) && $data['total'] > 0) {
                        return true;
                    }
                }
            }
            return false;
        });
    }
}