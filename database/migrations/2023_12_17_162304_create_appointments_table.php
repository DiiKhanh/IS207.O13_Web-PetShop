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
    // Example migration file
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('dog_item_id');
            $table->string('phone_number');
            $table->string('service');
            $table->string('date');
            $table->string('hour');
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->string('result')->nullable();
            $table->boolean('is_cancel')->default(false);

            // // Foreign key constraints
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('dog_item_id')->references('id')->on('dog_items');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
