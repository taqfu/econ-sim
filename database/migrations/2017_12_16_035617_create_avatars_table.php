<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
			$table->char('name', 255);
			$table->unsignedTinyInteger('age')->default(18);
			$table->dateTime('died_at')->nullable();
			$table->unsignedTinyInteger('health')->default(100);
			$table->dateTime('sleep_at')->useCurrent();
			$table->unsignedTinyInteger('sleep');
			$table->unsignedTinyInteger('sleep_req');
			$table->boolean('tired')->default(false);
			$table->boolean('exhausted')->default(false);

			$table->dateTime('eat_at')->useCurrent();
			$table->unsignedInteger('calories');
			$table->unsignedInteger('calories_req');
			$table->boolean('starving')->default(false);
			$table->unsignedTinyInteger('max_days_to_starve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatars');
    }
}
