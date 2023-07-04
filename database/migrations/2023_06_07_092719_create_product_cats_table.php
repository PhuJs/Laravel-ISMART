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
        Schema::create('product_cats', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('user_create', 255);
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->foreignId('parent_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_cats');
    }
};
