<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedIpToCampaign2022VotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('campaign2022_votes', 'created_ip')) {
            Schema::table('campaign2022_votes', function (Blueprint $table) {
                $table->string('created_ip', 15)->nullable(false)->comment('建立者ip')->after('vote_to');
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
        Schema::table('campaign2022_votes', function (Blueprint $table) {
            //
        });
    }
}
