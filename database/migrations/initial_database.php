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
            ['id' => '2', 'location' => 'Leeds', 'postcode' => 'LS2 7DA']
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
            ['id' => '11', 'category_id' => '4', 'name' => 'Move Shelf'],
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
            $table->foreignID('department_id')->references('id')->on('department')->onDelete('cascade');
        });
        DB::table('item')->insert([
            ['id' => '1', 'name' => 'Apple', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '2', 'name' => 'Orange', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '3', 'name' => 'Pear', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '4', 'name' => 'Pepsi', 'price' => '1.50', 'department_id' => '2'],
            ['id' => '5', 'name' => 'Pepsi Max', 'price' => '1.20', 'department_id' => '2'],
            ['id' => '6', 'name' => 'Fuck Laravel T-Shirt', 'price' => '0', 'department_id' => '3'],
            ['id' => '7', 'name' => 'Christmas Tree', 'price' => '24.99', 'department_id' => '4']
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
            ['id' => '4', 'name' => 'Floor']
        ]);

        Schema::create('store_item', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->double('price');
        });

        Schema::create('store_item_storage', function (Blueprint $table) {
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->double('quantity');
            $table->foreignID('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->timestamp('expiration_date')->nullable();
        });

        Schema::create('delivery_note', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->timestamp('date_time');
        });

        Schema::create('delivered_item', function (Blueprint $table) {
            $table->foreignID('delivery_note_id')->references('id')->on('delivery_note')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('quantity');
        });

        Schema::create('over_deliveries', function (Blueprint $table) {
            $table->foreignID('delivery_note_id')->references('id')->on('delivery_note')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->boolean('returned');
            $table->integer('quantity');
            $table->timestamp('date_time');
        });

        Schema::create('store_item_price_change', function (Blueprint $table) {
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('old_price');
            $table->double('new_price');
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

        Artisan::call('db:seed');

        for ($i = 1; $i < 108; $i++) {
            DB::table('store_item')->insert([
                ['id' => $i,'store_id' => '1', 'item_id' => $i, 'price' => '1']
            ]);
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
        Schema::dropIfExists('store_item_location_change');
        Schema::dropIfExists('store_item_price_change');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};