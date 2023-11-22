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
        Schema::create('overpayment_transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('amount', 10)->nullable();
            $table->integer('overpayment_id')->nullable();
            $table->integer('recipt_id')->nullable();
            $table->integer('return_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overpayment_transactions');
    }
};
