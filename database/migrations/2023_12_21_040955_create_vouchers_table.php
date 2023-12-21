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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('code')->unique();
            $table->string("discount_type");
            $table->decimal('discount_value', 10, 2);
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedInteger('max_usage')->nullable();
            $table->unsignedInteger('current_usage')->default(0);
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
        Schema::dropIfExists('vouchers');
    }
};
