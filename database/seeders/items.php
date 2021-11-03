<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class items extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $this->memasukkan_barang_ke_gudang();
        $this->mengeluarkan_barang();
    }

    public function mengeluarkan_barang()
    {
        DB::table('items')->insert([
            'gudang_id' => 2,
            'customer_id' => 4,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'out',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
        DB::table('items')->insert([
            'gudang_id' => 2,
            'customer_id' => 4,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'out',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
    }

    public function memasukkan_barang_ke_gudang()
    {
        DB::table('items')->insert([
            'gudang_id' => 2,
            'supplier_id' => 3,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'in',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
        DB::table('items')->insert([
            'gudang_id' => 2,
            'supplier_id' => 3,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'in',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
        DB::table('items')->insert([
            'gudang_id' => 2,
            'supplier_id' => 3,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'in',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
        DB::table('items')->insert([
            'gudang_id' => 2,
            'supplier_id' => 3,
            'item_name' => "lidah",
            'item_price' => 100,
            'item_weight' => 22.5,
            'status' => 'in',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
        ]);
    }
}
