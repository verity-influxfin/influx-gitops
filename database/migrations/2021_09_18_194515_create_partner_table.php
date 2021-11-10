<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('partner')) {
            Schema::create('partner', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255)->nullable()->comment('標題');
                $table->string('name', 255)->nullable()->comment('合作夥伴單位名稱');
                $table->longText('text')->nullable()->comment('內容');
                $table->string('imageSrc', 255)->nullable()->comment('圖片');
                $table->string('type', 255)->nullable()->comment('單位類型');
                $table->integer('order')->nullable()->comment('顯示排序');
                $table->string('link', 255)->nullable()->comment('跳轉連結');
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
        Schema::dropIfExists('partner');
    }
}
