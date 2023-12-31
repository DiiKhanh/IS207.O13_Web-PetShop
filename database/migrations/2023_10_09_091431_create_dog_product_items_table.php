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
        Schema::create('dog_product_items', function (Blueprint $table) {
            $table->id();
            $table-> string('ItemName');
            $table-> integer('Price');
            $table-> string('Category');
            $table-> string('Description');
            $table-> longText('Images');
            $table-> integer('Quantity');
            $table-> boolean('IsInStock');
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
        Schema::dropIfExists('dog_product_items');
    }
};
