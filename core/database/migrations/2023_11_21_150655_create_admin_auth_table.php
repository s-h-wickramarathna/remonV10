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
        Schema::create('admin_auth', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id');
            $table->decimal('amount', 10)->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->softDeletes();
            $table->string('job_no', 50)->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->integer('user_id')->nullable();
            $table->decimal('outstanding', 10)->nullable();
            $table->decimal('credit_limit', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_auth');
    }
};
