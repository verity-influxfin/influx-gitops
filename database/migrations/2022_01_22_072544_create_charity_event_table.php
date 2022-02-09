<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharityEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charity_event', function (Blueprint $table) {
            $table->integer('id')->nullable(false);
            $table->string('prefix', 1)->nullable(false)->comment('對應主站資料表 (a:charity/ m:ntu_press_conference)');
            $table->string('name', 50)->nullable(false)->comment('捐款⼈姓名/⾏號');
            $table->tinyInteger('type')->default(0)->comment('類型 (0:排行/ 1:即時)');
            $table->integer('amount')->nullable(false)->default(0)->comment('捐款⾦額');
            $table->tinyInteger('weight')->default(0)->comment('權值');
            $table->primary(['id', 'prefix']);
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
        Schema::dropIfExists('charity_event');
    }
}
