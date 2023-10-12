<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\DogItem;
use App\DogSpecies;
use   Illuminate\Contracts\Container\BindingResolutionException;

class DogSpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dog_species')->insert([
            ['DogSpeciesName' => 'Golden Retriever', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Alaska', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Husky', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Corgi', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Doberman', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Pitbull', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Lạp Xưởng', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Poodle', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Chihuahua', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Shiba', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Bulldog', 'IsDeleted' => false],
            ['DogSpeciesName' => 'Beagle', 'IsDeleted' => false]
        ]);
    }
}
