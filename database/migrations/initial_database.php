@ -0,0 +1,242 @@
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('location')->unique();
            $table->string('postcode');
        });
        DB::table('store')->insert([
            ['id' => '1', 'location' => 'Sheffield', 'postcode' => 'S1 2NH'],
            ['id' => '2', 'location' => 'Leeds', 'postcode' => 'LS1 4DY'],
            ['id' => '3', 'location' => 'Manchester', 'postcode' => 'M11 3DL'],
            ['id' => '4', 'location' => 'Liverpool', 'postcode' => 'L18 1DG']
        ]);

        Schema::create('users', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert(
            ['id' => '1', 'store_id' => '1', 'first_name' => 'First', 'last_name' => 'Last', 'email' => 'test@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18']
        );

        Schema::create('role', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });
        DB::table('role')->insert([
            ['id' => '1', 'name' => 'Manager'],
            ['id' => '2', 'name' => 'Salesperson'],
            ['id' => '3', 'name' => 'Purchaser'],
            ['id' => '4', 'name' => 'Stocker'],
            ['id' => '5', 'name' => 'Warehouse Operator'],
            ['id' => '6', 'name' => 'Optimiser'],
            ['id' => '7', 'name' => 'Admin']
        ]);

        Schema::create('category', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });
        DB::table('category')->insert([
            ['id' => '1', 'name' => 'Stock Management'],
            ['id' => '2', 'name' => 'Sales'],
            ['id' => '3', 'name' => 'Logistics'],
            ['id' => '4', 'name' => 'Inventory'],
            ['id' => '5', 'name' => 'Admin']
        ]);

        Schema::create('permission', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->string('name');
        });
        DB::table('permission')->insert([
            ['id' => '1', 'category_id' => '1', 'name' => 'Buy Products'],
            ['id' => '2', 'category_id' => '1', 'name' => 'View Stock'],
            ['id' => '3', 'category_id' => '1', 'name' => 'Add/Remove Stock'],
            ['id' => '4', 'category_id' => '1', 'name' => 'Generate Stock Reports'],
            ['id' => '5', 'category_id' => '2', 'name' => 'Sell Products'],
            ['id' => '6', 'category_id' => '2', 'name' => 'Generate Sales Invoices'],
            ['id' => '7', 'category_id' => '3', 'name' => 'Receive Stock'],
            ['id' => '8', 'category_id' => '3', 'name' => 'Generate Delivery Reports'],
            ['id' => '9', 'category_id' => '3', 'name' => 'Note over-deliveries'],
            ['id' => '10', 'category_id' => '3', 'name' => 'Complete over-deliveries'],
            ['id' => '11', 'category_id' => '4', 'name' => 'Move Stock'],
            ['id' => '12', 'category_id' => '4', 'name' => 'Stock Check'],
            ['id' => '13', 'category_id' => '4', 'name' => 'Generate Stock Check Reports'],
            ['id' => '14', 'category_id' => '5', 'name' => 'Create Users'],
            ['id' => '15', 'category_id' => '5', 'name' => 'Edit Users'],
            ['id' => '16', 'category_id' => '5', 'name' => 'Delete Users'],
            ['id' => '17', 'category_id' => '5', 'name' => 'Generare User Reports']
        ]);

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignID('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->foreignID('permission_id')->references('id')->on('permission')->onDelete('cascade');
        });
        DB::table('role_permission')->insert([
            ['role_id' => '1', 'permission_id' => '1'],
            ['role_id' => '1', 'permission_id' => '2'],
            ['role_id' => '1', 'permission_id' => '3'],
            ['role_id' => '1', 'permission_id' => '4'],
            ['role_id' => '1', 'permission_id' => '5'],
            ['role_id' => '1', 'permission_id' => '6'],
            ['role_id' => '1', 'permission_id' => '7'],
            ['role_id' => '1', 'permission_id' => '8'],
            ['role_id' => '1', 'permission_id' => '9'],
            ['role_id' => '1', 'permission_id' => '10'],
            ['role_id' => '1', 'permission_id' => '11'],
            ['role_id' => '1', 'permission_id' => '12'],
            ['role_id' => '1', 'permission_id' => '13'],
            ['role_id' => '1', 'permission_id' => '14'],
            ['role_id' => '1', 'permission_id' => '15'],
            ['role_id' => '1', 'permission_id' => '16'],
            ['role_id' => '1', 'permission_id' => '17'],
            ['role_id' => '2', 'permission_id' => '5'],
            ['role_id' => '3', 'permission_id' => '1'],
            ['role_id' => '3', 'permission_id' => '2'],
            ['role_id' => '3', 'permission_id' => '3'],
            ['role_id' => '3', 'permission_id' => '4'],
            ['role_id' => '3', 'permission_id' => '10'],
            ['role_id' => '4', 'permission_id' => '2'],
            ['role_id' => '4', 'permission_id' => '11'],
            ['role_id' => '4', 'permission_id' => '12'],
            ['role_id' => '4', 'permission_id' => '13'],
            ['role_id' => '5', 'permission_id' => '2'],
            ['role_id' => '5', 'permission_id' => '7'],
            ['role_id' => '5', 'permission_id' => '8'],
            ['role_id' => '5', 'permission_id' => '9'],
            ['role_id' => '6', 'permission_id' => '2'],
            ['role_id' => '6', 'permission_id' => '4'],
            ['role_id' => '6', 'permission_id' => '6'],
            ['role_id' => '6', 'permission_id' => '8'],
            ['role_id' => '6', 'permission_id' => '13'],
            ['role_id' => '7', 'permission_id' => '14'],
            ['role_id' => '7', 'permission_id' => '15'],
            ['role_id' => '7', 'permission_id' => '16'],
            ['role_id' => '7', 'permission_id' => '17']
        ]);

        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('role_id')->references('id')->on('role')->onDelete('cascade');
        });
        DB::table('user_role')->insert([
            ['user_id' => '1', 'role_id' => '1']
        ]);

        Schema::create('transaction', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->timestamp('date_time');
            $table->string('card');
        });

        Schema::create('department', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });
        DB::table('department')->insert([
            ['id' => '1', 'name' => 'Food'],
            ['id' => '2', 'name' => 'Drink'],
            ['id' => '3', 'name' => 'Clothing'],
            ['id' => '4', 'name' => 'Seasonal'],
            ['id' => '5', 'name' => 'Home']
        ]);

        Schema::create('item', function (Blueprint $table) {
            $table->id()->primary(); 
            $table->string('name'); 
            $table->double('price'); 
            $table->foreignId('department_id')->constrained('department')->onDelete('cascade');
            $table->timestamps(); 
        });
        DB::table('item')->insert([
            ['id' => '1', 'name' => 'Apple', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '2', 'name' => 'Orange', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '3', 'name' => 'Pear', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '4', 'name' => 'Banana', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '5', 'name' => 'Kiwi', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '6', 'name' => 'Pepsi', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '7', 'name' => 'Pepsi Max', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '8', 'name' => 'Water', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '9', 'name' => 'Orange Juice', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '10', 'name' => 'Milk', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '11', 'name' => 'Shirt', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '12', 'name' => 'Socks', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '13', 'name' => 'Pants', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '14', 'name' => 'Trousers', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '15', 'name' => 'Hat', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '16', 'name' => 'Christmas Tree', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '17', 'name' => 'Stocking', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '18', 'name' => 'Christmas Card', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '19', 'name' => 'Wreath', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '20', 'name' => 'Other Christmas Item', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '21', 'name' => 'Chair', 'price' => '0.99', 'department_id' => '5'],
            ['id' => '22', 'name' => 'Table', 'price' => '0.99', 'department_id' => '5'],
            ['id' => '23', 'name' => 'Lamp', 'price' => '0.99', 'department_id' => '5'],
            ['id' => '24', 'name' => 'Other Lamp', 'price' => '0.99', 'department_id' => '5'],
            ['id' => '25', 'name' => 'Another Lamp?!', 'price' => '0.99', 'department_id' => '5']
        ]);

        Schema::create('transaction_item', function (Blueprint $table) {
            $table->foreignID('transaction_id')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('price');
        });

        Schema::create('order', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->timestamp('date_time');
            $table->tinyInteger('fulfilled')->default(0);

        });

        Schema::create('order_item', function (Blueprint $table) {
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('ordered');
            $table->double('price');
        });
       
        Schema::create('location', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });
        DB::table('location')->insert([
            ['id' => '1', 'name' => 'Freezer'],
            ['id' => '2', 'name' => 'Fridge'],
            ['id' => '3', 'name' => 'Warehouse'],
            ['id' => '4', 'name' => 'Floor'],
            ['id' => '5', 'name' => 'Return_bay']
        ]);

        Schema::create('store_item', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('low-stock-amount');
            $table->double('price');
            $table->timestamp('last_spot_checked');
        });

        Schema::create('store_item_storage', function (Blueprint $table) {
            $table->id();
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->integer('quantity');
            $table->foreignID('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->timestamp('expiration_date')->nullable();
        });

        Schema::create('delivery_note', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->timestamp('date_time');
            $table->timestamps();
        });

        Schema::create('delivered_item', function (Blueprint $table) {
            $table->foreignID('delivery_note_id')->references('id')->on('delivery_note')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('over_deliveries', function (Blueprint $table) {
            $table->foreignID('delivery_note_id')->references('id')->on('delivery_note')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->boolean('returned');
            $table->integer('quantity');
            $table->timestamp('date_time');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        for ($store = 1; $store < 5; $store++) {
            for ($item = 1; $item < 26; $item++) {
                DB::table('store_item')->insert([
                    ['id' => ($item + (($store - 1)* 25)),'store_id' => $store, 'item_id' => $item, 'price' => '1.49', 'low-stock-amount' => '5']
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role');
        Schema::dropIfExists('permission');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('transaction');
        Schema::dropIfExists('department');
        Schema::dropIfExists('item');
        Schema::dropIfExists('transaction_item');
        Schema::dropIfExists('order');
        Schema::dropIfExists('order_item');
        Schema::dropIfExists('location');
        Schema::dropIfExists('store_item');
        Schema::dropIfExists('store_item_storage');
        Schema::dropIfExists('delivery_note');
        Schema::dropIfExists('delivered_item');
        Schema::dropIfExists('over_deliveries');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};