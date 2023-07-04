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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('product_code', 255)->unique();
            $table->text('desc')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount')->nullable();
            $table->integer('number_stock')->nullable();
            $table->string('thumbnail', 255);
            $table->text('product_detail')->nullable();
            $table->integer('status')->nullable();
            $table->string('user_create', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
