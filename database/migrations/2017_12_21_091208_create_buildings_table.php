<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->unsignedInteger('avatar_id')->nullable()->default(null);
            $table->unsignedInteger('building_type_id');
            $table->unsignedInteger('begin_x');
            $table->unsignedInteger('begin_y');
            $table->unsignedInteger('end_x');
            $table->unsignedInteger('end_y');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
