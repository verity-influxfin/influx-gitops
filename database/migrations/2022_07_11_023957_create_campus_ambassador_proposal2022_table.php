<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusAmbassadorProposal2022Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus_ambassador_proposal_2022', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 60)->nullable(true)->comment('團體名稱(個人組無)');
            $table->string('proposal', 30)->nullable(true)->comment('校園推廣企劃提案');
            $table->string('portfolio', 30)->nullable(true)->comment('作品集');
            $table->string('video', 30)->nullable(true)->comment('影片上傳');
            $table->string('video_link', 100)->nullable(true)->comment('影片連結');
            $table->string('created_ip', 15)->nullable(false)->comment('建立者ip');
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
        Schema::dropIfExists('campus_ambassador_proposal_2022');
    }
}
