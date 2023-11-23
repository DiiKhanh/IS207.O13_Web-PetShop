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
            $table->enum('payment_status', ['Chua thanh toan', 'Da thanh toan', 'Da thanh toan online'])->default('Chua thanh toan');
            $table->enum('delivery_status', ['Huy', 'Thanh cong', 'Dang giao', 'Dang lay hang', 'Dang cho xac nhan'])->default('Dang cho xac nhan');
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
