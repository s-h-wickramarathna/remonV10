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
        Schema::create('ramon_album_size', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('sizes', 50)->default('0');
            $table->dateTime('created_at')->nullable();
            $table->tinyInteger('active_status')->nullable()->default(1);
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
        Schema::dropIfExists('ramon_album_size');
    }
};
