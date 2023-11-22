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
        Schema::create('remon_customer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('f_name', 50);
            $table->string('l_name', 50);
            $table->string('address', 200)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('telephone', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at')->nullable();
            $table->integer('status')->nullable()->default(1);
            $table->integer('type');
            $table->decimal('credit_limit', 10)->nullable();
            $table->integer('credit_period')->nullable()->default(45);
            $table->integer('marketeer_id')->default(0);
            $table->string('nic', 50)->nullable();
            $table->integer('area')->nullable();
            $table->integer('is_credit_limit_block')->default(1);
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remon_customer');
    }
};
