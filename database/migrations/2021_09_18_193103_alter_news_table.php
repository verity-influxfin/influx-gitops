<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                if (Schema::hasColumn('news', 'ID')) {
                    DB::statement("ALTER TABLE `news` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('news', 'post_date')) {
                    $table->dropColumn('post_date');
                }
                if (Schema::hasColumn('news', 'order')) {
                    $table->dropColumn('order');
                }
                if (Schema::hasColumn('news', 'status')) {
                    DB::statement("ALTER TABLE `news` CHANGE `status` `isActive` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on' COMMENT '是否呈現 on:是,off:否'");
                }
                if (! Schema::hasColumn('news', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('news', 'updated_at')){
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
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                if (Schema::hasColumn('news', 'id')) {
                    DB::statement("ALTER TABLE `news` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('news', 'post_date')) {
                    $table->dateTime('post_date')->nullable();
                }
                if (Schema::hasColumn('news', 'order')) {
                    $table->integer('order', 11)->nullable()->default('0');
                }
                if (! Schema::hasColumn('news', 'status')) {
                    if(Schema::hasColumn('news', 'isActive')){
                        DB::statement("ALTER TABLE `news` CHANGE `isActive` `status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'publish'");
                    }else{
                        $table->string('status', 20)->nullable()->default('on');
                    }
                }
                if (Schema::hasColumn('news', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('news', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
