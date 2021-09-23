<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('banner')) {
            Schema::create('banner', function (Blueprint $table) {
                $table->id();
                $table->string('desktop', 255)->nullable()->comment('網頁版圖片連結');
                $table->string('mobile', 255)->nullable()->comment('手機版版圖片連結');
                $table->string('link', 20)->nullable()->comment('圖片跳轉連結');
                $table->string('type', 20)->nullable()->default('index')->comment('圖片呈現頁面位置');
                $table->string('isActive', 20)->nullable()->default('on')->comment('是否呈現 on:是,off:否');
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
        Schema::dropIfExists('banner');
    }
}
