<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusAmbassador2022Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus_ambassador_2022', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('identity')->nullable(false)->comment('身份別(1:個人組,2:團體組組長,3:團體組組員)');
            $table->integer('proposal_id')->nullable(false)->comment('提案作品id');
            $table->string('name', 50)->nullable(false)->comment('姓名');
            $table->string('phone', 10)->nullable(false)->unique()->comment('手機');
            $table->string('email', 100)->nullable(false)->comment('Email');
            $table->string('school', 50)->nullable(false)->comment('就讀學校');
            $table->string('major', 100)->nullable(false)->comment('科系');
            $table->tinyInteger('grade')->nullable(false)->comment('年級');
            $table->string('school_city', 10)->nullable(false)->comment('學校所在地');
            $table->date('birthday')->nullable(false)->comment('生日');
            $table->string('social', 100)->nullable(false)->comment('個人社群連結');
            $table->string('photo', 30)->nullable(false)->comment('生活照');
            $table->string('introduction_brief', 255)->nullable(false)->comment('一句話形容你自己');
            $table->text('introduction')->nullable(false)->comment('自我介紹');
            $table->tinyInteger('qa_1')->nullable(false)->comment('問答一');
            $table->tinyInteger('qa_2')->nullable(false)->comment('問答二');
            $table->tinyInteger('qa_3')->nullable(false)->comment('問答三');
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
        Schema::dropIfExists('campus_ambassador_2022');
    }
}
