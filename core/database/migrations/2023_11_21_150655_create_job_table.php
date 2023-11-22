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
        Schema::create('job', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id');
            $table->string('job_no', 100);
            $table->integer('status')->default(1);
            $table->string('album', 100);
            $table->string('type', 100)->nullable()->default('0');
            $table->string('count', 100)->nullable()->default('0');
            $table->string('extra_page', 100)->nullable()->default('0');
            $table->string('size', 100);
            $table->string('cover', 100)->nullable()->default('0');
            $table->string('box', 100)->nullable()->default('0');
            $table->string('discount', 100)->nullable()->default('0');
            $table->string('couple_name', 100);
            $table->string('remark', 100);
            $table->dateTime('ended_at')->nullable();
            $table->string('img_url')->nullable();
            $table->string('original_img', 200)->nullable();
            $table->date('due_date')->nullable();
            $table->integer('allocate_user')->nullable();
            $table->integer('create_by');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('marketer_confirm')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
    }
};
