<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesTest extends TestCase {
    protected $transactionId; // Placeholder for the transaction ID
    use RefreshDatabase;

    public function test_login_as_salesperson() {
        $response = $this->post('/login', [
            'email' => 'test@email.com',
            'password' => '123'
        ]);
        $response->assertRedirect('/dashboard');
    }

    // Testing to ensure salesperson can make a sale.
    public function test_make_sale() {
        return false; // Placeholder
    }

    // Testing to ensure sales person can view transaction details. 
    public function test_view_transaction_details() {
        // Auth
        $user = User::where('email', 'test@email.com')->first();
        $this->actingAs($user);
        $this->transactionId = 1; // Define transaction ID from info on DB
        // Get response
        $response = $this->post(route('sales.viewDetails'), ['id' => $this->transactionId]);
        $response->assertStatus(200);
        $response->assertViewIs('Sales.transaction-details');
        $response->assertViewHas(['transaction', 'items']);
    }

    public function test_invoice_download() {
        // Auth
        $user = User::where('email', 'test@email.com')->first();
        $this->actingAs($user);
        $this->transactionId = 1;
        // Response
        $response = $this->post(route('sales.downloadInvoice'), ['id' => $this->transactionId]);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}