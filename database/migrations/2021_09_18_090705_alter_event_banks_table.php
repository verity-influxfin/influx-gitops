<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_banks')) {
            Schema::table('event_banks', function (Blueprint $table) {
                if (Schema::hasColumn('event_banks', 'ID')) {
                    DB::statement("ALTER TABLE `event_banks` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('event_banks', 'created_at')) {
                    DB::statement("ALTER TABLE `event_banks` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;");
                }
                if (! Schema::hasColumn('event_banks', 'updated_at')){
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
        if (Schema::hasTable('event_banks')) {
            Schema::table('event_banks', function (Blueprint $table) {
                if (Schema::hasColumn('event_banks', 'id')) {
                    DB::statement("ALTER TABLE `event_banks` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('event_banks', 'created_at')) {
                    DB::statement("ALTER TABLE `event_banks` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT NULL;");
                }
                if (Schema::hasColumn('event_banks', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
