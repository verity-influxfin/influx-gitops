<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImgUrlToJobApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_application', function (Blueprint $table) {
            $table->string('img_url')->nullable(true)->comment('大頭貼');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_application', function (Blueprint $table) {
            $table->dropColumn('img_url');
        });
    }
}
