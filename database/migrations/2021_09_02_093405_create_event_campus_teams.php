<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCampusTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_campus_teams', function (Blueprint $table) {
            $table->id();
            $table->text('school', 100)->comment('學校');
            $table->text('name', 255)->comment('團隊名稱');
            $table->text('intro', 300)->comment('團隊介紹')->nullable(true);
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
        Schema::dropIfExists('event_campus_teams');
    }
}
