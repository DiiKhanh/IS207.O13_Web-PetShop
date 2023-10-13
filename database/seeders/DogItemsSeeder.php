<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DogItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy danh sách các loài chó từ bảng dog_species
        $dogSpecies = DB::table('dog_species')->get();
        $currentTimestamp = Carbon::now();


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
