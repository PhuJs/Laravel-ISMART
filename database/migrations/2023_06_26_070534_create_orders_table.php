<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 255)->unique();
            $table->string('customer_name', 255);
            $table->string('email', 255)->nullable();
            $table->string('address', 255);
            $table->string('phone', 255);
            $table->string('note', 500)->nullable();
            $table->unsignedBigInteger('total_order');
            $table->integer('num_order');
            $table->integer('payment')->default(1);
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
