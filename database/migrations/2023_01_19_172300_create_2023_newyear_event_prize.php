<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create2023NewyearEventPrize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('2023_newyear_event_prize', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('使用者編號');
            $table->integer('prize_status')->comment('得獎狀態: 0:未中獎, 1:新戶中獎, 2:舊戶中獎');
            $table->string('created_ip', 30)->nullable()->comment('使用者IP');
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
        Schema::dropIfExists('2023_newyear_event_prize');
    }
}
