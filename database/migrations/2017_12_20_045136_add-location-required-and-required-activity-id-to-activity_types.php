<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationRequiredAndRequiredActivityIdToActivityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_types', function (Blueprint $table) {
			$table->boolean('location_required')->default(0);
			$table->unsignedInteger('related_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_types', function (Blueprint $table) {
            //
        });
    }
}
