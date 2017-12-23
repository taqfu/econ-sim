<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->char('name', 255);
            $table->unsignedInteger('building_type_id');
            $table->unsignedInteger('max_storage');
            $table->boolean('public')->default(false);
            $table->boolean('enclosed')->default(true);
            $table->boolean('sleep')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_types');
    }
}
