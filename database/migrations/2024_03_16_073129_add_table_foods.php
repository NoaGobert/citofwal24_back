<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('description');
            $table->string('diet')->nullable();
            $table->unsignedBigInteger('food_category_id');
            $table->uuid('donator_id');
            $table->uuid('receiver_id')->nullable();
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();


            $table->foreign('donator_id')->references('uuid')->on('users');
            $table->foreign('receiver_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};