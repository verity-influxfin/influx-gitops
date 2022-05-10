<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaign2022sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign2022s', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable(false)->unique();
            $table->string('nick_name')->comment('會員暱稱');
            $table->string('file_name')->nullable(false)->comment('檔案名稱');
            $table->integer('votes')->nullable(false)->default(0)->index()->comment('總票數');
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
        Schema::dropIfExists('campaign2022s');
    }
}
