<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('post_title', 255)->nullable()->comment('標題');
                $table->longText('post_content')->nullable()->comment('內容');
                $table->string('image_url', 255)->nullable()->comment('圖片');
                $table->string('isActive', 255)->nullable()->default('on')->comment('是否呈現 on:是,off:否');
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
        Schema::dropIfExists('news');
    }
}
