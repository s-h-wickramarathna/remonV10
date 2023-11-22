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
        Schema::create('recipt', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('amount', 20, 2);
            $table->string('recipt_no', 80)->index('recipt_no_2');
            $table->dateTime('recipt_date');
            $table->integer('user_id');
            $table->integer('type');
            $table->integer('location_id')->nullable();
            $table->integer('print_count')->nullable()->default(0);
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
            $table->integer('account_id')->nullable();
            $table->text('remark')->nullable();

            $table->unique(['recipt_no'], 'recipt_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipt');
    }
};
