<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleQaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_qa', function (Blueprint $table) {
            $table->id();
            $table->string('job_position', 255)->comment('職位名稱');
            $table->string('name', 15)->comment('姓名');
            $table->string('age', 5)->comment('年齡');
            $table->json('question', 3000)->comment('面試問答');
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
        Schema::dropIfExists('google_qa');
    }
}
