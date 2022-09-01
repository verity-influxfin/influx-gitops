<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToKnowledgeArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knowledge_article', function (Blueprint $table) {
            $table->string('meta_og_image')->nullable(true)->comment('og:image')->after('isActive');
            $table->string('meta_og_title')->nullable(true)->comment('og:title')->after('isActive');
            $table->string('meta_og_description')->nullable(true)->comment('og:description')->after('isActive');
            $table->string('meta_description')->nullable(true)->comment('網頁description')->after('isActive');
            $table->string('web_title')->nullable(true)->comment('網頁title')->after('isActive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_article', function (Blueprint $table) {
            //
        });
    }
}
