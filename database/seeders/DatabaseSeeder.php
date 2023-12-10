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
            'updated_at' => $currentTimestamp,
            'phoneNumber' => '0123456789',
            'firstName' => 'shop',
            'lastName' => 'pet'
        ]);

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







        // Lấy danh sách các loài chó từ bảng dog_species
        $dogSpecies = DB::table('dog_species')->get();

        // Tạo 3 mẫu dữ liệu cho bảng dog_items
        DB::table('dog_items')->insert([
            [
                'DogName' => 'Max',
                'DogSpecies' => $dogSpecies[0]->id,
                'Price' => 2000000,
                'Color' => 'Black',
                'Sex' => 'Male',
                'Age' => 2,
                'Origin' => 'Vietnam',
                'HealthStatus' => 'Healthy',
                'Description' => 'Max is a friendly and playful dog.',
                'Images' => json_encode(['image1.jpg', 'image2.jpg', 'image3.jpg']),
                'IsInStock' => true,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'DogName' => 'Bella',
                'DogSpecies' => $dogSpecies[1]->id,
                'Price' => 3000000,
                'Color' => 'Brown',
                'Sex' => 'Female',
                'Age' => 3,
                'Origin' => 'Vietnam',
                'HealthStatus' => 'Healthy',
                'Description' => 'Bella is a beautiful and calm dog.',
                'Images' => json_encode(['image4.jpg', 'image5.jpg', 'image6.jpg']),
                'IsInStock' => true,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'DogName' => 'Charlie',
                'DogSpecies' => $dogSpecies[2]->id,
                'Price' => 2500000,
                'Color' => 'White',
                'Sex' => 'Male',
                'Age' => 1,
                'Origin' => 'Vietnam',
                'HealthStatus' => 'Healthy',
                'Description' => "Charlie is an energetic and friendly dog.",
                "Images" => json_encode(["image7.jpg", "image8.jpg", "image9.jpg"]),
                'IsInStock' => true,
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ]
        ]);
    }
}
