<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

//chạy php artisan db:seed là tự động seed hết
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
        $this->call(DogSpeciesSeeder::class);
        $this->call(DogItemsSeeder::class);
    }
}
