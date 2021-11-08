<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('event_users')) {
            Schema::create('event_users', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 10)->nullable()->comment('註冊電話');
                $table->string('promo', 30)->nullable()->comment('註冊推薦碼');
                $table->string('email', 255)->nullable();
                $table->string('promo_info', 255)->nullable()->comment('推薦人資訊');
                $table->string('created_ip', 30)->nullable();
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
        Schema::dropIfExists('event_users');
    }
}
