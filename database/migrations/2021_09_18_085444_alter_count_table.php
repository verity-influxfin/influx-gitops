<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('count')) {
            Schema::table('count', function (Blueprint $table) {
                if (Schema::hasColumn('count', 'ID')) {
                    DB::statement("ALTER TABLE `count` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('count', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('count', 'updated_at')){
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
        if (Schema::hasTable('count')) {
            Schema::table('count', function (Blueprint $table) {
                if (Schema::hasColumn('count', 'id')) {
                    DB::statement("ALTER TABLE `count` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('count', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('count', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
