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
        Schema::create('fonts-list', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('type', 30)->nullable();
            $table->string('icon', 30)->nullable();
            $table->string('unicode', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fonts-list');
    }
};
