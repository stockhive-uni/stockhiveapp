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
     * 
     * Made this by reading and learning Laravel's default code - Rob
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
        DB::table('users')->insert([
            ['id' => '1', 'store_id' => '1', 'first_name' => 'Manager', 'last_name' => 'Name', 'email' => 'Manager1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '2', 'store_id' => '1', 'first_name' => 'Salesperson', 'last_name' => 'Name', 'email' => 'Salesperson1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '3', 'store_id' => '1', 'first_name' => 'Purchaser', 'last_name' => 'Name', 'email' => 'Purchaser1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '4', 'store_id' => '1', 'first_name' => 'Stocker', 'last_name' => 'Name', 'email' => 'Stocker1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '5', 'store_id' => '1', 'first_name' => 'WarehouseOperator', 'last_name' => 'Name', 'email' => 'WarehouseOperator1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '6', 'store_id' => '1', 'first_name' => 'Optimiser', 'last_name' => 'Name', 'email' => 'Optimiser1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '7', 'store_id' => '1', 'first_name' => 'Admin', 'last_name' => 'Name', 'email' => 'Admin1@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '8', 'store_id' => '2', 'first_name' => 'Manager', 'last_name' => 'Name', 'email' => 'Manager2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '9', 'store_id' => '2', 'first_name' => 'Salesperson', 'last_name' => 'Name', 'email' => 'Salesperson2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '10', 'store_id' => '2', 'first_name' => 'Purchaser', 'last_name' => 'Name', 'email' => 'Purchaser2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '11', 'store_id' => '2', 'first_name' => 'Stocker', 'last_name' => 'Name', 'email' => 'Stocker2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '12', 'store_id' => '2', 'first_name' => 'WarehouseOperator', 'last_name' => 'Name', 'email' => 'WarehouseOperator2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '13', 'store_id' => '2', 'first_name' => 'Optimiser', 'last_name' => 'Name', 'email' => 'Optimiser2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '14', 'store_id' => '2', 'first_name' => 'Admin', 'last_name' => 'Name', 'email' => 'Admin2@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '15', 'store_id' => '3', 'first_name' => 'Manager', 'last_name' => 'Name', 'email' => 'Manager3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '16', 'store_id' => '3', 'first_name' => 'Salesperson', 'last_name' => 'Name', 'email' => 'Salesperson3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '17', 'store_id' => '3', 'first_name' => 'Purchaser', 'last_name' => 'Name', 'email' => 'Purchaser3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '18', 'store_id' => '3', 'first_name' => 'Stocker', 'last_name' => 'Name', 'email' => 'Stocker3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '19', 'store_id' => '3', 'first_name' => 'WarehouseOperator', 'last_name' => 'Name', 'email' => 'WarehouseOperator3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '20', 'store_id' => '3', 'first_name' => 'Optimiser', 'last_name' => 'Name', 'email' => 'Optimiser3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '21', 'store_id' => '3', 'first_name' => 'Admin', 'last_name' => 'Name', 'email' => 'Admin3@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '22', 'store_id' => '4', 'first_name' => 'Manager', 'last_name' => 'Name', 'email' => 'Manager4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '23', 'store_id' => '4', 'first_name' => 'Salesperson', 'last_name' => 'Name', 'email' => 'Salesperson4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '24', 'store_id' => '4', 'first_name' => 'Purchaser', 'last_name' => 'Name', 'email' => 'Purchaser4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '25', 'store_id' => '4', 'first_name' => 'Stocker', 'last_name' => 'Name', 'email' => 'Stocker4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '26', 'store_id' => '4', 'first_name' => 'WarehouseOperator', 'last_name' => 'Name', 'email' => 'WarehouseOperator4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '27', 'store_id' => '4', 'first_name' => 'Optimiser', 'last_name' => 'Name', 'email' => 'Optimiser4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18'],
            ['id' => '28', 'store_id' => '4', 'first_name' => 'Admin', 'last_name' => 'Name', 'email' => 'Admin4@email.com', 'password' => '$2y$10$i27yIT02tT4MPs4rvTiT7eJcJ6xdxIJHghyjGmWWwNDocTWKZ5NZe', 'created_at' => '2024-11-13 18:43:18', 'updated_at' => '2024-11-13 18:43:18']
        ]);

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
        for ($store = 1; $store < 5; $store++) {
            for ($role = 1; $role < 8; $role++) {
                DB::table('user_role')->insert([
                    ['user_id' => (($store - 1) * 7) + $role, 'role_id' => $role]
                ]);
            }
        }

        Schema::create('transaction', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->timestamp('date_time');
            $table->string('card');
        });
        DB::table('transaction')->insert([
            ['id' => '1', 'user_id' => '1', 'store_id' => '1', 'date_time' => '2024-11-13 14:37:09', 'card' => 'example card'],
            ['id' => '2', 'user_id' => '1', 'store_id' => '1', 'date_time' => '2024-10-13 14:37:09', 'card' => 'example card'],
            ['id' => '3', 'user_id' => '1', 'store_id' => '1', 'date_time' => '2024-09-13 14:37:09', 'card' => 'example card'],
            ['id' => '4', 'user_id' => '1', 'store_id' => '1', 'date_time' => '2024-08-13 14:37:09', 'card' => 'example card'],
            ['id' => '5', 'user_id' => '1', 'store_id' => '1', 'date_time' => '2024-07-13 14:37:09', 'card' => 'example card']
        ]);

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
            ['id' => '1', 'name' => 'Apple', 'price' => '0.49', 'department_id' => '1'],
            ['id' => '2', 'name' => 'Orange', 'price' => '0.99', 'department_id' => '1'],
            ['id' => '3', 'name' => 'Pear', 'price' => '1.49', 'department_id' => '1'],
            ['id' => '4', 'name' => 'Banana', 'price' => '1.99', 'department_id' => '1'],
            ['id' => '5', 'name' => 'Kiwi', 'price' => '2.49', 'department_id' => '1'],
            ['id' => '6', 'name' => 'Pepsi', 'price' => '0.49', 'department_id' => '2'],
            ['id' => '7', 'name' => 'Pepsi Max', 'price' => '0.99', 'department_id' => '2'],
            ['id' => '8', 'name' => 'Water', 'price' => '1.49', 'department_id' => '2'],
            ['id' => '9', 'name' => 'Milk', 'price' => '1.99', 'department_id' => '2'],
            ['id' => '10', 'name' => 'Pilk', 'price' => '2.49', 'department_id' => '2'],
            ['id' => '11', 'name' => 'Shirt', 'price' => '0.49', 'department_id' => '3'],
            ['id' => '12', 'name' => 'Socks', 'price' => '0.99', 'department_id' => '3'],
            ['id' => '13', 'name' => 'Pants', 'price' => '1.49', 'department_id' => '3'],
            ['id' => '14', 'name' => 'Trousers', 'price' => '1.99', 'department_id' => '3'],
            ['id' => '15', 'name' => 'Hat', 'price' => '2.49', 'department_id' => '3'],
            ['id' => '16', 'name' => 'Christmas Tree', 'price' => '0.49', 'department_id' => '4'],
            ['id' => '17', 'name' => 'Stocking', 'price' => '0.99', 'department_id' => '4'],
            ['id' => '18', 'name' => 'Christmas Card', 'price' => '1.49', 'department_id' => '4'],
            ['id' => '19', 'name' => 'Wreath', 'price' => '1.99', 'department_id' => '4'],
            ['id' => '20', 'name' => 'Other Christmas Item', 'price' => '2.49', 'department_id' => '4'],
            ['id' => '21', 'name' => 'Chair', 'price' => '0.49', 'department_id' => '5'],
            ['id' => '22', 'name' => 'Table', 'price' => '0.99', 'department_id' => '5'],
            ['id' => '23', 'name' => 'Lamp', 'price' => '1.49', 'department_id' => '5'],
            ['id' => '24', 'name' => 'Other Lamp', 'price' => '1.99', 'department_id' => '5'],
            ['id' => '25', 'name' => 'Another Lamp?!', 'price' => '2.49', 'department_id' => '5']
        ]);

        Schema::create('transaction_item', function (Blueprint $table) {
            $table->foreignID('transaction_id')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('price');
        });
        DB::table('transaction_item')->insert([
            ['transaction_id' => '1', 'item_id' => '1', 'quantity' => '1', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '2', 'quantity' => '2', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '3', 'quantity' => '3', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '4', 'quantity' => '4', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '5', 'quantity' => '5', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '6', 'quantity' => '6', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '7', 'quantity' => '7', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '8', 'quantity' => '8', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '9', 'quantity' => '9', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '10', 'quantity' => '10', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '11', 'quantity' => '11', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '12', 'quantity' => '12', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '13', 'quantity' => '13', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '14', 'quantity' => '14', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '15', 'quantity' => '15', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '16', 'quantity' => '16', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '17', 'quantity' => '17', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '18', 'quantity' => '18', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '19', 'quantity' => '19', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '20', 'quantity' => '20', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '21', 'quantity' => '21', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '22', 'quantity' => '22', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '23', 'quantity' => '23', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '24', 'quantity' => '24', 'price' => '0.49'],
            ['transaction_id' => '1', 'item_id' => '25', 'quantity' => '25', 'price' => '0.49'],
            ['transaction_id' => '2', 'item_id' => '1', 'quantity' => '5', 'price' => '1.49'],
            ['transaction_id' => '2', 'item_id' => '2', 'quantity' => '2', 'price' => '0.99'],
            ['transaction_id' => '2', 'item_id' => '3', 'quantity' => '10', 'price' => '0.49'],
            ['transaction_id' => '3', 'item_id' => '1', 'quantity' => '2', 'price' => '0.99'],
            ['transaction_id' => '4', 'item_id' => '1', 'quantity' => '13', 'price' => '1.99'],
            ['transaction_id' => '5', 'item_id' => '1', 'quantity' => '7', 'price' => '1.49']
        ]);

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
            for ($category = 1; $category < 6; $category++) {
                for ($item = 1; $item < 6; $item++) {
                    $item_id = (($category - 1) * 5) + $item;
                    $id = (($store - 1) * 25) + $item_id;
                    DB::table('store_item')->insert([
                        ['id' => $id, 'store_id' => $store, 'item_id' => $item_id, 'price' => (($category * 0.50) - 0.01), 'low-stock-amount' => (8 - $category)]
                    ]);
                }
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