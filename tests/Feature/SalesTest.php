<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SalesTest extends TestCase {
    protected $transactionId; // Placeholder for the transaction ID - Adam
    protected $storeId;
    use RefreshDatabase;

    public function test_login_as_salesperson() {
        $response = $this->post('/login', [
            'email' => 'Manager1@email.com',
            'password' => '123'
        ]);
        $response->assertRedirect('/dashboard');
    }

    // Testing to ensure salesperson can make a sale.
    public function test_make_sale() {
        // Auth
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $this->storeId = 1; // Use the placeholder variable.
        // Get items
        $items = DB::table('item')
            ->join('store_item', 'store_item.item_id', '=', 'item.id')
            ->where('store_item.store_id', $user->store_id)
            ->select('item.id', 'item.name', 'store_item.price')
            ->limit(3) 
            ->get();
        $data = [
            'id' => $items->pluck('id')->toArray(),
            'quantity' => [2, 3, 8]
        ];
        // Response
        $response = $this->post(route('sales.confirmTransaction'), $data);
        $response->assertStatus(200);
        $response->assertViewIs('Sales.sales');
        $response->assertViewHas('message', 'Transaction successfully processed');
    }

    // Testing to ensure sales person can view transaction details. 
    public function test_view_transaction_details() {
        // Auth
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $this->transactionId = 1; 
        // Get response
        $response = $this->get(route('sales.viewDetails', ['id' => $this->transactionId]));
        $response->assertStatus(200);
        $response->assertViewIs('Sales.transaction-details');
        $response->assertViewHas(['transaction', 'items']);
    }

    public function test_invoice_download() {
        // Auth
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $this->transactionId = 1;
        // Response
        $response = $this->post(route('sales.downloadInvoice'), ['id' => $this->transactionId]);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf'); // Ensure downloaded content is a PDF - https://stackoverflow.com/a/53937452 + https://laravel.com/docs/11.x/http-tests#assert-header - Adam
    }
}