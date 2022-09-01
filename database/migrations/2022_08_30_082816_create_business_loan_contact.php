<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLoanContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_loan_contact', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable(false)->comment('姓名');
            $table->tinyInteger('gender')->nullable(false)->comment('身分性別(1:男,2:女)');
            $table->string('company_name', 120)->nullable(false)->comment('公司名稱');
            $table->string('email')->nullable(false)->comment('電子信箱');
            $table->tinyInteger('contact_time')->nullable(false)->comment('聯絡時間(1:隨時,2:上午,3:下午)');
            $table->text('reason')->nullable(false)->comment('需求原因');
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
        Schema::dropIfExists('business_loan_contact');
    }
}
