<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('event_banks')) {
            Schema::create('event_banks', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 10)->nullable()->comment('電話');
                $table->string('name', 30)->nullable()->comment('姓名');
                $table->string('email', 255)->nullable();
                $table->string('page_from', 11)->nullable()->comment('資料填寫來源 index:首頁,event:活動頁');
                $table->string('created_ip', 30)->nullable()->comment('來源IP');
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
        Schema::dropIfExists('event_banks');
    }
}
