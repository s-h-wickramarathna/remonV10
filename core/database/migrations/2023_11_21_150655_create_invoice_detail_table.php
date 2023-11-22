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
        Schema::create('invoice_detail', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->nullable()->index('product_id_idx');
            $table->integer('batch_id');
            $table->decimal('unit_price', 10)->nullable();
            $table->integer('qty')->nullable();
            $table->string('free_qty', 45)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->integer('invoice_id')->nullable()->index('fk_invoice_detail_invoice1_idx');
            $table->integer('group_id');
            $table->integer('price_book_detail_id')->nullable();
            $table->integer('order_detail_type')->default(1)->comment('invoice qty = 1,auto free = 2,open free = 3');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at')->nullable();
            $table->unsignedInteger('editor')->nullable();
            $table->decimal('editor_commision', 10, 0)->nullable();
            $table->decimal('discount', 10)->nullable()->default(0);
            $table->decimal('old_discount', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_detail');
    }
};
