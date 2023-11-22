<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_level_config', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('job_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('employee_type')->nullable();
            $table->integer('job_link')->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_level_config');
    }
};
