<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('partner')) {
            Schema::table('partner', function (Blueprint $table) {
                if (Schema::hasColumn('partner', 'ID')) {
                    DB::statement("ALTER TABLE `partner` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('partner', 'type')) {
                    DB::statement("ALTER TABLE `partner` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '單位類型 society:?,edu:大學院校'");
                }
                if (! Schema::hasColumn('partner', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('partner', 'updated_at')){
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
        if (Schema::hasTable('partner')) {
            Schema::table('partner', function (Blueprint $table) {
                if (Schema::hasColumn('partner', 'id')) {
                    DB::statement("ALTER TABLE `partner` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('partner', 'status')) {
                    DB::statement("ALTER TABLE `partner` CHANGE `isActive` `status` ENUM('society','edu') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL");
                }
                if (Schema::hasColumn('partner', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('partner', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
