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
        Schema::create('invoice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('job_no', 100);
            $table->integer('rep_id')->nullable()->index('rep_id_idx');
            $table->integer('location_id')->nullable()->index('outlet_id_idx');
            $table->integer('loading_id');
            $table->integer('brand_id');
            $table->tinyInteger('status')->nullable()->default(1);
            $table->string('remark', 90)->nullable();
            $table->string('payment_status', 45)->nullable()->default('0');
            $table->decimal('total', 10);
            $table->string('manual_id', 45)->nullable()->index('manual_id');
            $table->dateTime('pay_date')->nullable();
            $table->boolean('print_status')->nullable()->default(false);
            $table->integer('delivery_status')->default(0);
            $table->integer('device')->comment('0 = web,1 = palm');
            $table->dateTime('created_date');
            $table->dateTime('created_at')->nullable();
            $table->timestamp('timestamp')->useCurrentOnUpdate()->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->decimal('discount', 10);
            $table->string('lat', 45)->nullable();
            $table->string('lon', 45)->nullable();
            $table->integer('marketer_confirm')->default(1);
            $table->integer('territory_id')->nullable();
            $table->integer('route_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->text('couple_name')->nullable();
            $table->integer('payment_type')->nullable();
            $table->integer('discount_reset')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
};
