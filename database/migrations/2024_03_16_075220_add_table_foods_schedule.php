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
        Schema::create('foods_schedule', function (Blueprint $table) {
            $table->uuid('foods_uuid');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();

            $table->foreign('foods_uuid')
                ->references('uuid')
                ->on('foods')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods_schedule');
    }
};