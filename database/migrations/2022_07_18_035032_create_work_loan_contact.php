<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkLoanContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_loan_contact', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable(false)->comment('您的尊稱');
            $table->string('phone', 10)->nullable(false)->comment('手機號碼');
            $table->tinyInteger('gender')->nullable(false)->comment('身分證性別');
            $table->string('line', 40)->nullable(true)->comment('Line帳號');
            $table->string('email', 100)->nullable(false)->comment('電子信箱');
            $table->text('reason')->nullable(false)->comment('資金需求');
            $table->tinyInteger('contact_time')->nullable(false)->comment('聯絡時間(1:隨時,2:上午,3:下午)');
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
        Schema::dropIfExists('work_loan_contact');
    }
}
