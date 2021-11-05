<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('event_users')) {
            Schema::table('event_users', function (Blueprint $table) {
                if (Schema::hasColumn('event_users', 'ID')) {
                    DB::statement("ALTER TABLE `event_users` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL  AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('event_users', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }else{
                    DB::statement("ALTER TABLE `event_users` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;");
                }
                if (! Schema::hasColumn('event_users', 'updated_at')){
                    $table->timestamp('updated_at')->nullable();
                }
                if (! Schema::hasColumn('event_users', 'promo_info')){
                    $table->string('promo_info', 255)->nullable()->comment('推薦人資訊');
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
        if (Schema::hasTable('event_users')) {
            Schema::table('event_users', function (Blueprint $table) {
                if (Schema::hasColumn('event_users', 'id')) {
                    DB::statement("ALTER TABLE `event_users` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('event_users', 'created_at')) {
                    DB::statement("ALTER TABLE `event_banks` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT NULL;");
                }
                if (Schema::hasColumn('event_users', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
                if (Schema::hasColumn('event_users', 'promo_info')){
                    $table->dropColumn('promo_info');
                }
            });
        }
    }
}
