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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('address', 255);
            // Đơn giá không thể âm và không tự tăng đơn vị
            $table->integer('total', false, true);
            $table->enum('shipment', ['Đang lấy hàng', 'Đang giao', 'Thành công', 'Huỷ']);
            $table->enum('status', ['Chưa thanh toán', 'Đã thanh toán online', 'Đã thanh toán']);
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
        Schema::dropIfExists('orders');
    }
};
