<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaAltToKnowledgeArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knowledge_article', function (Blueprint $table) {
            //
            $table->string('media_alt')->nullable('true')->after('media_link');
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
            $table->dropColumn('media_alt');
        });
    }
}
