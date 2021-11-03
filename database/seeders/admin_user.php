<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class admin_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // admin
        DB::table('users')->insert([
           
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password'    => Hash::make('admin'),
            'is_admin' => TRUE,
            'is_gudang' => TRUE,
            'is_customer' => FALSE,
            'is_supplier' => FALSE,
            'name' => 'admin',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
            // 'api_token' => Str::random(60)
        ]);
        // gudang
        DB::table('users')->insert([
           
            'username' => 'gudang',
            'email' => 'gudang@gmail.com',
            'password'    => Hash::make('gudang'),
            'is_admin' =>  FALSE,
            'is_gudang' => TRUE,
            'is_customer' => FALSE,
            'is_supplier' => FALSE,
            'name' => 'gudang',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
            // 'api_token' => Str::random(60)
        ]);
        // supplier
        DB::table('users')->insert([
           
            'username' => 'supplier',
            'email' => 'supplier@gmail.com',
            'password'    => Hash::make('supplier'),
            'is_admin' => FALSE,
            'is_gudang' => FALSE,
            'is_customer' => FALSE,
            'is_supplier' => TRUE,
            'name' => 'supplier',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
            // 'api_token' => Str::random(60)
        ]);
        // customer
        DB::table('users')->insert([
           
            'username' => 'customer',
            'email' => 'customer@gmail.com',
            'password'    => Hash::make('customer'),
            'is_admin' => FALSE,
            'is_gudang' => FALSE,
            'is_customer' => TRUE,
            'is_supplier' => FALSE,
            'name' => 'customer',
            'created_at' =>  new Carbon('now'),
            'updated_at' =>  new Carbon('now'),
            // 'api_token' => Str::random(60)
        ]);
        
    }
}
