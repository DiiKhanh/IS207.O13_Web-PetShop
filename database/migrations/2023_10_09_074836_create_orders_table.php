<?php

use App\Models\User;
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
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->integer('total_price')->default(0);
            $table->string('address');
            $table->string('phone_number');
            $table->enum('payment_status', ['Chưa thanh toán', 'Đã thanh toán', 'Đã thanh toán online'])->default('Chưa thanh toán');
            $table->enum('delivery_status', ['Hủy', 'Thành công', 'Đang giao', 'Đang lấy hàng', 'Đang chờ xác nhận'])->default('Đang chờ xác nhận');
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
