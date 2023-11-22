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
        Schema::create('job_master_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent')->nullable();
            $table->integer('lft');
            $table->integer('rgt')->nullable();
            $table->integer('depth');
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
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
        Schema::dropIfExists('job_master_data');
    }
};
