<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('meta_og_image')->nullable(true)->comment('og:image')->after('pinned');
            $table->string('meta_og_title')->nullable(true)->comment('og:title')->after('pinned');
            $table->string('meta_og_description')->nullable(true)->comment('og:description')->after('pinned');
            $table->string('meta_description')->nullable(true)->comment('網頁description')->after('pinned');
            $table->string('web_title')->nullable(true)->comment('網頁title')->after('pinned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            //
        });
    }
}
