<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaign2022AddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign2022_adds', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign2022s_id')->nullable(false)->comment('活動作品id');
            $table->integer('votes')->nullable(false)->default(0)->comment('增加的票數');
            $table->index('campaign2022s_id');
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
        Schema::dropIfExists('campaign2022_adds');
    }
}
