@ -0,0 +1,242 @@
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        Schema::create('permission', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignID('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->foreignID('permission_id')->references('id')->on('permission')->onDelete('cascade');
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('role_id')->references('id')->on('role')->onDelete('cascade');
        });

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

        Schema::create('item', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('price');
            $table->foreignID('department_id')->references('id')->on('department')->onDelete('cascade');
            $table->integer('order_limit');
        });

        Schema::create('transaction_item', function (Blueprint $table) {
            $table->foreignID('transaction_id')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
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
            $table->integer('price');
        });

        Schema::create('location', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
        });

        Schema::create('store_item', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->integer('price');
        });

        Schema::create('store_item_storage', function (Blueprint $table) {
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->integer('price');
            $table->foreignID('location_id')->references('id')->on('location')->onDelete('cascade');
        });

        Schema::create('delivery_note', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->timestamp('date_time');
        });

        Schema::create('delivered_item', function (Blueprint $table) {
            $table->foreignID('delivery_note_id')->references('id')->on('delivery_note')->onDelete('cascade');
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('quantity');
        });

        Schema::create('over_deliveries', function (Blueprint $table) {
            $table->foreignID('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreignID('item_id')->references('id')->on('item')->onDelete('cascade');
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->boolean('returned');
            $table->integer('quantity');
            $table->timestamp('date_time');
        });

        Schema::create('store_item_location_change', function (Blueprint $table) {
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignID('old_location')->references('id')->on('location')->onDelete('cascade');
            $table->foreignID('new_location')->references('id')->on('location')->onDelete('cascade');
        });

        Schema::create('store_item_price_change', function (Blueprint $table) {
            $table->foreignID('store_item_id')->references('id')->on('store_item')->onDelete('cascade');
            $table->foreignID('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('old_price');
            $table->integer('new_price');
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