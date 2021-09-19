<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('interview')) {
            Schema::table('interview', function (Blueprint $table) {
                if (Schema::hasColumn('interview', 'ID')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('interview', 'category')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `category` `category` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL");
                }
                if (Schema::hasColumn('interview', 'post_modified')) {
                    $table->dropColumn('post_modified');
                }
                if (Schema::hasColumn('interview', 'rank')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `rank` `rank` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'student'");
                }
                if (Schema::hasColumn('interview', 'isRead')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `isRead` `isRead` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1' COMMENT '0:no,1:yes'");
                }
                if (Schema::hasColumn('interview', 'isActive')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `isActive` `isActive` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on'");
                }
                if (! Schema::hasColumn('interview', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('interview', 'updated_at')){
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
        if (Schema::hasTable('interview')) {
            Schema::table('interview', function (Blueprint $table) {
                if (Schema::hasColumn('interview', 'id')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('interview', 'category')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `category` `category` ENUM('loan','invest') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL");
                }
                if (! Schema::hasColumn('interview', 'post_modified')) {
                    $table->timestamp('post_modified')->nullable();
                }
                if (Schema::hasColumn('interview', 'rank')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `category` `category` ENUM('officeWorker','student') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'student'");
                }
                if (Schema::hasColumn('interview', 'isRead')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `isRead` `isRead` ENUM('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1' COMMENT '0:no,1:yes'");
                }
                if (Schema::hasColumn('interview', 'isActive')) {
                    DB::statement("ALTER TABLE `interview` CHANGE `isActive` `isActive` ENUM('on','off') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on'");
                }
                if (Schema::hasColumn('interview', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('interview', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
