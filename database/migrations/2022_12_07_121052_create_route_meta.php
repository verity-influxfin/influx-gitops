<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_meta', function (Blueprint $table) {
            $table->id();
            $table->string('route_path')->comment('路徑');
            $table->string('meta_og_image')->nullable(true)->comment('og:image');
            $table->string('meta_og_title')->nullable(true)->comment('og:title');
            $table->string('meta_og_description')->nullable(true)->comment('og:description');
            $table->string('meta_description')->nullable(true)->comment('網頁description');
            $table->string('web_title')->nullable(true)->comment('網頁title');
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
        Schema::dropIfExists('route_meta');
    }
}
