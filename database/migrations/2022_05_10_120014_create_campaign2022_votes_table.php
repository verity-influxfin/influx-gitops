<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaign2022VotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign2022_votes', function (Blueprint $table) {
            $table->id();
            $table->integer('vote_from')->nullable(false)->comment('投票人user_id');
            $table->integer('vote_to')->nullable(false)->comment('被投摽人user_id');
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
        Schema::dropIfExists('campaign2022_votes');
    }
}
