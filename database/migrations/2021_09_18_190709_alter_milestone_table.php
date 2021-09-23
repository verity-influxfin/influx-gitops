<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMilestoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('milestone')) {
            Schema::table('milestone', function (Blueprint $table) {
                if (Schema::hasColumn('milestone', 'ID')) {
                    DB::statement("ALTER TABLE `milestone` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL  AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('milestone', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('milestone', 'updated_at')){
                    $table->timestamp('updated_at')->nullable();
                }
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
        if (Schema::hasTable('milestone')) {
            Schema::table('milestone', function (Blueprint $table) {
                if (Schema::hasColumn('milestone', 'id')) {
                    DB::statement("ALTER TABLE `milestone` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('milestone', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('milestone', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
