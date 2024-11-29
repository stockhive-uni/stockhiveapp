<?php
/*
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::table('order')->insert([
            ['id' => 1, 'user_id' => 1, 'store_id' => 1,  'date_time' => now(),'fulfilled' => 0],
            ['id' => 2,  'user_id' => 2,  'store_id' => 2,  'date_time' => now()->subDays(value: 1),'fulfilled' =>0  ],
            ['id' => 3,  'user_id' => 3,  'store_id' => 1,'date_time' => now()->subDays(2),'fulfilled' =>0 ],
            ['id' => 4, 'user_id' => 4, 'store_id' => 1,  'date_time' => now(), 'fulfilled' => 1,],
        ]);

        DB::table('order_item')->insert([
            [ 'order_id' => 1, 'item_id' => 1, 'ordered' => 10, 'price' => 5.99,],
            [ 'order_id' => 1,'item_id' => 2, 'ordered' => 20,'price' => 3.49,],
            [ 'order_id' => 2, 'item_id' => 3,'ordered' => 5, 'price' => 12.99,],
            [  'order_id' => 3,  'item_id' => 1, 'ordered' => 15, 'price' => 4.99,],    
        ]);        

        DB::table('delivery_note')->insert([
            [ 'id' => 1,'user_id' => 1,'order_id' => 1,'date_time' => Carbon::now()->addDays(1), ],
            ['id' => 2,'user_id' => 2, 'order_id' => 2, 'date_time' => Carbon::now()->addDays(2),],
        ]);

        DB::table('delivered_item')->insert([
            ['delivery_note_id' => 1,    'item_id' => 1,    'quantity' => 10, ],
            ['delivery_note_id' => 1,  'item_id' => 2,  'quantity' => 5,    ],
            [ 'delivery_note_id' => 2,'item_id' => 3,'quantity' => 20, ],
        ]);
    
    }
}
