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
        Schema::create('remon_product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('product_name')->nullable();
            $table->text('description')->nullable();
            $table->string('pack_size', 45)->nullable();
            $table->string('barcode', 150)->nullable();
            $table->string('short_code', 200)->nullable();
            $table->string('tax_code', 45)->nullable();
            $table->string('product_alias', 450)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamp('timestamp')->nullable();
            $table->integer('product_category_id');
            $table->integer('brand_id');
            $table->integer('range_id');
            $table->integer('size_id')->nullable()->index('size_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remon_product');
    }
};
