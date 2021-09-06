<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCampusMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_campus_members', function (Blueprint $table) {
            $table->id();
            $table->text('name', 100)->comment('姓名');
            $table->text('dept', 100)->comment('科系');
            $table->smallInteger('grade')->comment('年級');
            $table->text('mobile', 10)->comment('手機');
            $table->text('email', 255)->comment('Email');
            $table->text('self_intro', 300)->comment('自我簡介');
            $table->text('resume', 100)->comment('個人履歷');
            $table->text('motivation', 300)->comment('報名動機');
            $table->text('portfolio', 100)->nullable(true)->comment('個人作品集');
            $table->text('fb_link', 2083)->nullable(true)->comment('個人臉書連結');
            $table->text('ig_link', 2083)->nullable(true)->comment('個人IG連結');
            $table->text('bonus', 2083)->nullable(true)->comment('其他加分項');
            $table->unsignedBigInteger('team_id')->comment('團隊 ID');
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
        Schema::dropIfExists('event_campus_members');
    }
}
