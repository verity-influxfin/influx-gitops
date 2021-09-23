<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('milestone')) {
            Schema::create('milestone', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255)->nullable()->comment('歷程標題');
                $table->date('hook_date')->nullable()->comment('歷程時間');
                $table->longText('content')->nullable()->comment('內容');
                $table->string('icon', 255)->nullable()->comment('歷程圖片');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone');
    }
}
