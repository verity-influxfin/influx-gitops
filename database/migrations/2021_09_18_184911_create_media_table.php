<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('media_link', 255)->nullable()->comment('媒體名稱');
                $table->date('date')->nullable()->comment('報導時間');
                $table->string('title', 255)->nullable()->comment('標題');
                $table->string('link', 255)->nullable()->comment('報導連結');
                $table->longText('content')->nullable()->comment('內文');
                $table->string('imgSrc', 255)->nullable()->comment('圖片連結');
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
        Schema::dropIfExists('media');
    }
}
