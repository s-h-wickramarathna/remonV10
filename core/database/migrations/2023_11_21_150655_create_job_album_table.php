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
        Schema::create('job_album', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('job_id');
            $table->integer('album_id');
            $table->integer('album_no');
            $table->integer('pages')->nullable();
            $table->string('album_size', 50)->nullable();
            $table->integer('user_level')->nullable();
            $table->integer('user')->nullable();
            $table->integer('nxt_level')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_album');
    }
};
