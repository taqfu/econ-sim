<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixShipmentRelatedFieldsOnItemTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_types', function (Blueprint $table) {
			$table->unsignedInteger('shipment_per_ship')->default(0)->change();
			$table->unsignedInteger('shipment_per_person')->default(0)->change();
			$table->unsignedInteger('reserve')->default(0)->change();
			$table->unsignedInteger('shipment_unload_to_room_id')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_types', function (Blueprint $table) {
            //
        });
    }
}
