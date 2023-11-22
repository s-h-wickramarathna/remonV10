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
        Schema::create('job_new', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id');
            $table->string('job_no', 100);
            $table->integer('status')->default(1);
            $table->string('count', 100)->nullable()->default('0');
            $table->string('extra_page', 100)->nullable()->default('0');
            $table->string('discount', 100)->nullable()->default('0');
            $table->string('couple_name', 100);
            $table->text('remark')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->string('img_url')->nullable();
            $table->string('original_img', 200)->nullable();
            $table->string('album', 100)->nullable();
            $table->string('album_size', 100)->nullable();
            $table->date('due_date')->nullable();
            $table->integer('allocate_user')->nullable();
            $table->integer('user_status')->default(0);
            $table->string('delivery', 50)->nullable();
            $table->integer('create_by');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('marketer_confirm')->nullable()->default(1);
            $table->string('qr_1', 100)->nullable();
            $table->string('qr_2', 100)->nullable();
            $table->string('qr_3', 100)->nullable();
            $table->string('qr_4', 100)->nullable();
            $table->string('qr_5', 100)->nullable();
            $table->text('attachment_1')->nullable();
            $table->text('attachment_2')->nullable();
            $table->text('attachment_3')->nullable();
            $table->text('attachment_4')->nullable();
            $table->text('attachment_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_new');
    }
};
