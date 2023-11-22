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
        Schema::create('marketeer_target', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->index('employee_id_idx');
            $table->decimal('value', 12);
            $table->dateTime('from');
            $table->timestamp('to')->useCurrentOnUpdate()->useCurrent();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamp('timestamp')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketeer_target');
    }
};
