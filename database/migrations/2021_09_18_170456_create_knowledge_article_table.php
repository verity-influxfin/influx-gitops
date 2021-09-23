<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('knowledge_article')) {
            Schema::create('knowledge_article', function (Blueprint $table) {
                $table->id();
                $table->string('media_link', 255)->nullable()->comment('圖片連結');
                $table->string('video_link', 255)->nullable()->comment('影片連結');
                $table->longText('post_content')->nullable()->comment('發布內容');
                $table->string('post_title', 255)->nullable()->comment('發布標題');
                $table->string('type', 20)->nullable()->comment('發布類型');
                $table->string('isActive', 20)->nullable()->comment('是否上架 on:是,off:否');
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
        Schema::dropIfExists('knowledge_article');
    }
}
