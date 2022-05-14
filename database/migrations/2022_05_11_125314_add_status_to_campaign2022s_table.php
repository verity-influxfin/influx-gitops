<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCampaign2022sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('campaign2022s', 'status')) {
            Schema::table('campaign2022s', function (Blueprint $table) {
                $table->tinyInteger('status')
                    ->nullable(false)
                    ->default(0)
                    ->comment('是否已處理蒙太奇(0:否1:是)')
                    ->after('votes');
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
        Schema::table('campaign2022s', function (Blueprint $table) {
            //
        });
    }
}
