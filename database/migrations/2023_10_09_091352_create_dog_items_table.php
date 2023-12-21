<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dog_items', function (Blueprint $table) {
            $table->id();
            $table->string('DogName');
            //$table->foreignId('DogSpecies')->constrained('DogSpecies', 'DogSpeciesId'); // Tạo cột species kiểu unsigned big integer, là khóa ngoại tham chiếu đến cột id của bảng dog_species_tbl
            $table->foreignId('DogSpecies');
            $table->integer('Price');
            $table->string('Color');
            $table->string('Sex');
            $table->integer('Age');
            $table->string('Origin');
            $table->string('HealthStatus');
            $table->string('Description');
            $table->longText('Images');
            $table->boolean('IsInStock')->nullable();
            // $table->boolean('IsDeleted'); // Tạo cột is_deleted kiểu boolean
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dog_items');
    }
};
