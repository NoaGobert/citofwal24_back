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
        Schema::create('foods_status', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['available', 'reserved', 'delivered', 'no-show', 'unrequested', 'undelivered', 'sleep', 'deleted']);
            $table->uuid('foods_uuid');
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
        Schema::dropIfExists('foods_status');
    }
};