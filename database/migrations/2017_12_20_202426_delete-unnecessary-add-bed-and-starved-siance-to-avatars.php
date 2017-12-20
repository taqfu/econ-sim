<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUnnecessaryAddBedAndStarvedSianceToAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avatars', function (Blueprint $table) {
			$table->dropColumn('sleep_at');
			$table->dropColumn('eat_at');
			$table->dropColumn('activity');
			$table->dropColumn('starving');
			$table->unsignedInteger('bed_x')->nullable()->default(null);
			$table->unsignedInteger('bed_y')->nullable()->default(null);
			$table->dateTime('starved_since')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            //
        });
    }
}
