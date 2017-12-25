<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoryToAvatars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avatars', function (Blueprint $table) {
			$table->unsignedTinyInteger('inventory_weight');
			$table->unsignedTinyInteger('inventory_weight_limit');
			$table->unsignedTinyInteger('inventory_size');
			$table->unsignedTinyInteger('inventory_size_limit');
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
