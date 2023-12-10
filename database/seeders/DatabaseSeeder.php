<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();
        DB::table('users')->insert([
            'name' => 'petshop',
            'email' => 'petshop@gmail.com',
            'password' => Hash::make('petshop'),
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp
        ]);
        DB::table('orders')->insert([
            'user_id' => '1',
            'address' => '123f',
            'total' => 1000,
            'shipment' => 'Thành công',
            'status' => 'Đã thanh toán',
            'deleted_at' => null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp
        ]);
    }
}
