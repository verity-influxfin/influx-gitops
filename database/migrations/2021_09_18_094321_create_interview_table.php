<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('interview')) {
            Schema::create('interview', function (Blueprint $table) {
                $table->id();
                $table->string('type', 20)->nullable();
                $table->string('post_title', 255)->nullable()->comment('用戶名稱');
                $table->longText('feedback')->nullable()->comment('使用心得');
                $table->string('video_link', 255)->nullable()->comment('影片連結');
                $table->string('category', 20)->nullable()->comment('用戶在平台身份 invest:投資,loan:借款');
                $table->string('rank', 20)->nullable()->comment('用戶身份 officeWorker:上班族,student:學生');
                $table->string('imageSrc', 255)->nullable()->comment('圖片連結');
                $table->string('isRead', 20)->nullable()->comment('不知道幹嘛的 0:?,1:?');
                $table->string('isActive', 20)->nullable()->comment('使否呈現 on:是,off:否');
                $table->string('amount', 20)->nullable()->default(0)->comment('借貸金額');
                $table->string('rate', 20)->nullable()->default(0)->comment('借貸利率');
                $table->string('period_range', 20)->nullable()->default(0)->comment('期數');
                $table->string('spend_day', 20)->nullable()->default(0)->comment('媒合時間');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interview');
    }
}
