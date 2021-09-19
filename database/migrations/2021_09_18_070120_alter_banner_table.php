<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasTable('banner')) {
            Schema::table('banner', function (Blueprint $table) {
                if (Schema::hasColumn('banner', 'ID')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('banner', 'post_modified')) {
                    $table->dropColumn('post_modified');
                }
                if (Schema::hasColumn('banner', 'type')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'index'");
                }
                if (Schema::hasColumn('banner', 'isActive')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `isActive` `isActive` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on'");
                }
                if (! Schema::hasColumn('banner', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('banner', 'updated_at')){
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
        //
        if (Schema::hasTable('banner')) {
            Schema::table('banner', function (Blueprint $table) {
                if (Schema::hasColumn('banner', 'id')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('banner', 'post_modified')) {
                    $table->dateTime('post_modified');
                }
                if (Schema::hasColumn('banner', 'type')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `type` `type` ENUM('transfer','invest','engineer','freshGraduate','college','index') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'index'");
                }
                if (Schema::hasColumn('banner', 'isActive')) {
                    DB::statement("ALTER TABLE `banner` CHANGE `isActive` `v` ENUM('on','off') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on'");
                }
                if (Schema::hasColumn('banner', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('banner', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
