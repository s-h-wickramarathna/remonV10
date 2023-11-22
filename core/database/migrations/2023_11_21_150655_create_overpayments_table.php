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
        Schema::create('overpayments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('reference_id')->comment('ralaton to pyament type tbl');
            $table->double('amount', 20, 2);
            $table->dateTime('over_paid_date');
            $table->integer('location_id')->comment('related to location tbl loaction id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
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
        Schema::dropIfExists('overpayments');
    }
};
