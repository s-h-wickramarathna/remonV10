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
        Schema::create('credit_note', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('credit_amount', 20, 2);
            $table->string('invoice_no', 20);
            $table->integer('invoice_id');
            $table->double('credit_due', 20, 2)->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
            $table->double('invoice_amount', 20, 2)->nullable();
            $table->integer('create_by')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_note');
    }
};
