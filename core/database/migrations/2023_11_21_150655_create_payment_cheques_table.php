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
        Schema::create('payment_cheques', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('cheque_date');
            $table->string('cheque_no', 50);
            $table->integer('cheque_bank_id');
            $table->double('cheque_amount', 20, 2);
            $table->integer('recipt_id');
            $table->integer('status')->nullable()->default(1);
            $table->string('remark', 500)->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
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
        Schema::dropIfExists('payment_cheques');
    }
};
