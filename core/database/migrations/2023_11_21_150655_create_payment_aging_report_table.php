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
        Schema::create('payment_aging_report', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('manual_id', 50)->nullable();
            $table->string('job_no', 50)->nullable();
            $table->string('customer_name', 150)->nullable();
            $table->decimal('total', 10)->nullable();
            $table->decimal('invoice_due', 10)->nullable();
            $table->integer('no_of_days')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('marketeer_id')->nullable();
            $table->integer('customer')->nullable();
            $table->string('area', 150)->nullable();
            $table->string('couple_name', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_aging_report');
    }
};
