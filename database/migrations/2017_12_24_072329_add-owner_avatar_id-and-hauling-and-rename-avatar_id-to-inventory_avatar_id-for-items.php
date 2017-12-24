<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerAvatarIdAndHaulingAndRenameAvatarIdToInventoryAvatarIdForItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
			$table->unsignedInteger('owner_avatar_id')->nullable()->default(null);		
			$table->renameColumn('avatar_id', 'inventory_avatar_id');
			$table->boolean('hauling')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
