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
        Schema::create('post_cats', function (Blueprint $table) {
            $table->id();
            $table->string('cat_name', 100);
            $table->unsignedInteger('status')->default(1);
            $table->text('desc')->nullable();
            $table->unsignedInteger('parent_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_cats');
    }
};
