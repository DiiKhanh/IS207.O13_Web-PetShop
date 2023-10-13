<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DogSpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();
        DB::table('dog_species')->insert([
            ['DogSpeciesName' => 'Golden Retriever',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Alaska',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Husky',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Corgi',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Doberman',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Pitbull',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Lạp Xưởng',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Poodle',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Chihuahua',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Shiba',  'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['DogSpeciesName' => 'Bulldog',   'created_at' =>  $currentTimestamp, 'updated_at' =>  $currentTimestamp],
            ['DogSpeciesName' => 'Beagle', 'created_at' =>  $currentTimestamp, 'updated_at' =>  $currentTimestamp]
        ]);
    }
}
