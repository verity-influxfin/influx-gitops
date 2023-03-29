<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application', function (Blueprint $table) {
            $table->id();
            $table->string('job_position', 255)->comment('職位名稱');
            $table->string('name', 15)->comment('姓名');
            $table->string('blood_type', 4)->nullable(true)->comment('血型');
            $table->integer('height')->nullable(true)->comment('身高');
            $table->integer('weight')->nullable(true)->comment('體重');
            $table->timestamp('birthday')->nullable(true)->comment('生日');
            $table->string('marriage', 15)->nullable(true)->comment('婚姻狀況');
            $table->string('id_number', 15)->nullable(true)->comment('身分證字號');
            $table->string('hobby', 128)->nullable(true)->comment('興趣');
            $table->string('address', 128)->comment('戶籍地址');
            $table->string('mailing_address', 128)->nullable(true)->comment('通訊地址');
            $table->string('phone', 20)->nullable(true)->comment('住家電話');
            $table->string('mobile_phone', 20)->nullable(true)->comment('行動電話');
            $table->string('email', 50)->nullable(true)->comment('電子信箱');
            $table->string('education', 20)->nullable(true)->comment('最高學歷');
            $table->string('expertise', 50)->nullable(true)->comment('專長');
            $table->json('work_experiences', 255)->nullable(true)->comment('工作經歷');
            $table->string('wrote_person', 15)->nullable(true)->comment('填表人');
            $table->timestamp('wrote_date')->nullable(true)->comment('填寫日期');
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
        Schema::dropIfExists('job_application');
    }
}
