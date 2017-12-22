<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('employer_avatar_id')->nullable()->default(null);
            $table->unsignedInteger('employee_avatar_id')->nullable()->default(null);
            $table->unsignedInteger('silver_wage');
            $table->unsignedInteger('gold_wage');
            $table->unsignedInteger('building_id');
            $table->dateTime('hired_at')->nullable()->default(null);
            $table->dateTime('fired_at')->nullable()->default(null);
            $table->dateTime('promoted_at')->nullable()->default(null);
            $table->unsignedInteger('previous_job_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
