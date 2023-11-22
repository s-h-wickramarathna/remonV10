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
        Schema::create('ramon_cover', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('cover', 50)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('active_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ramon_cover');
    }
};
