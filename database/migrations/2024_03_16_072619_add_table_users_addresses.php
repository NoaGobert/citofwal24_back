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
        Schema::create('users_addresses', function (Blueprint $table) {
            $table->uuid('users_uuid');
            $table->string('street');
            $table->string('number');
            $table->string('zip');
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('region');
            $table->timestamps();

            $table->foreign('users_uuid')
                ->references('uuid')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_addresses');
    }
};