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
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('description');
            $table->uuid('group_owner');
            $table->foreign('group_owner')->references('uuid')->on('users');
            $table->timestamps();
        });

        Schema::create('users_groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')->references('uuid')->on('users');
            $table->uuid('group_uuid');
            $table->foreign('group_uuid')->references('uuid')->on('groups');
            $table->timestamps();
        });

        Schema::table('foods', function (Blueprint $table) {
            $table->uuid('group_uuid');
            $table->foreign('group_uuid')->references('uuid')->on('groups');
            $table->decimal('lat');
            $table->decimal('lon');
            $table->string('location_description');
            $table->enum('food_type', ['handmade', 'storebought', 'other']);
            $table->unsignedInteger('confirmation_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users_groups');
        Schema::table('foods', function (Blueprint $table) {
            $table->dropForeign(['group_uuid']);
            $table->dropColumn('group_uuid');
            $table->dropColumn('lat');
            $table->dropColumn('lon');
            $table->dropColumn('location_description');
            $table->dropColumn('food_type');
            $table->dropColumn('confirmation_code');
        });
    }
};
