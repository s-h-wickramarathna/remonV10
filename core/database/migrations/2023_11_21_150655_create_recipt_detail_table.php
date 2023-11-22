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
        Schema::create('recipt_detail', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('payment_amount', 20, 2);
            $table->integer('recipt_id');
            $table->integer('invoice_id');
            $table->double('invoice_due', 20, 2)->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('recipt_detail');
    }
};
