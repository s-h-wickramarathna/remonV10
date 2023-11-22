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
        Schema::create('tbl_sync', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->dateTime('created_at')->nullable();
            $table->string('token')->nullable();
            $table->integer('user_id')->nullable();
            $table->longText('content')->nullable();
            $table->string('uplodedType')->nullable();
            $table->longText('response_content')->nullable();
            $table->dateTime('response_timestamp')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->longText('pure_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_sync');
    }
};
