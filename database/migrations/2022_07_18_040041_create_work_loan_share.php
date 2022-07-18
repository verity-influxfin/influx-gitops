<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkLoanShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_loan_share', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable(false)->comment('使用者編號');
            $table->text('experience')->nullable(false)->comment('借款體驗');
            $table->string('created_ip', 15)->nullable(false)->comment('建立者ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_loan_share');
    }
}
