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
        Schema::create('employee', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name', 90);
            $table->string('last_name', 90);
            $table->text('address')->nullable();
            $table->string('mobile', 45)->nullable();
            $table->string('land', 45)->nullable();
            $table->string('email', 45);
            $table->string('code', 20)->nullable();
            $table->decimal('credit_limit', 10)->default(0);
            $table->integer('parent');
            $table->string('cheque_name', 50)->nullable();
            $table->string('business_name', 200);
            $table->integer('lft');
            $table->integer('rgt');
            $table->integer('depth');
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('employee_type_id')->index('fk_dsi_employee_dsi_employee_type1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
};
