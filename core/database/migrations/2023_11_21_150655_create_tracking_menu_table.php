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
        Schema::create('tracking_menu', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('label');
            $table->string('link');
            $table->string('icon')->nullable();
            $table->integer('parent')->nullable();
            $table->text('permissions')->nullable();
            $table->integer('lft');
            $table->integer('rgt')->nullable();
            $table->integer('depth');
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_menu');
    }
};
