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
        Schema::create('invoice_discount', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('rule_id')->nullable()->default(0);
            $table->integer('invoice_id')->nullable()->default(0);
            $table->integer('product_id');
            $table->double('discount', 12, 2)->nullable()->default(0);
            $table->dateTime('added_date')->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->timestamp('timestamp')->useCurrentOnUpdate()->useCurrent();
            $table->integer('discount_group_id');
            $table->integer('discount_type')->nullable()->comment('auto discount = 1,open discount = 2');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->decimal('old_discount', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_discount');
    }
};
