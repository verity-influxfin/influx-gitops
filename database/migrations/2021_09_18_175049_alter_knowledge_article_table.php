<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKnowledgeArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('knowledge_article')) {
            Schema::table('knowledge_article', function (Blueprint $table) {
                if (Schema::hasColumn('knowledge_article', 'ID')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('knowledge_article', 'post_author')) {
                    $table->dropColumn('post_author');
                }
                if (Schema::hasColumn('knowledge_article', 'post_date')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `post_date` `created_at` TIMESTAMP NULL DEFAULT NULL");
                }
                if (Schema::hasColumn('knowledge_article', 'type')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'article'");
                }
                if (Schema::hasColumn('knowledge_article', 'status')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `status` `isActive` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'on' COMMENT '是否呈現 on:是,off:否'");
                    DB::table('knowledge_article')->where('isActive','publish')->update(["isActive" => "on"]);
                    DB::table('knowledge_article')->where('isActive','inherit')->update(["isActive" => "off"]);
                    // DB::raw("SET SQL_SAFE_UPDATES=0;UPDATE `knowledge_article` SET `isActive` = 'on' WHERE (`isActive` = 'publish');UPDATE `knowledge_article` SET `isActive` = 'off' WHERE (`isActive` = 'inherit');SET SQL_SAFE_UPDATES=1");
                }
                if (Schema::hasColumn('knowledge_article', 'post_modified')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `post_modified` `updated_at` TIMESTAMP NULL DEFAULT NULL");
                }
                if (Schema::hasColumn('knowledge_article', 'category')) {
                    // DB::raw("SET SQL_SAFE_UPDATES=0;UPDATE `knowledge_article` SET `type` = 'investtonic' WHERE (`category` = 'investtonic');SET SQL_SAFE_UPDATES=1;");
                    DB::table('knowledge_article')->where('category','investtonic')->update(["type" => "investtonic"]);
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('knowledge_article', 'order')) {
                    $table->dropColumn('order');
                }
                if (! Schema::hasColumn('knowledge_article', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('knowledge_article', 'updated_at')){
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
        if (Schema::hasTable('knowledge_article')) {
            Schema::table('knowledge_article', function (Blueprint $table) {
                if (Schema::hasColumn('knowledge_article', 'id')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('knowledge_article', 'post_author')) {
                    $table->integer('post_author', 10)->nullable()->default('0');
                }
                if (! Schema::hasColumn('knowledge_article', 'post_date')) {
                    $table->dateTime('post_date')->nullable();
                }
                if (! Schema::hasColumn('knowledge_article', 'type')) {
                    DB::statement("ALTER TABLE `knowledge_article` CHANGE `type` `type` ENUM('video','article') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'article'");
                }
                if (! Schema::hasColumn('knowledge_article', 'status')) {
                    if(Schema::hasColumn('knowledge_article', 'isActive')){
                        DB::statement("ALTER TABLE `knowledge_article` CHANGE `isActive` `status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'publish'");
                    }else{
                        $table->string('status', 20)->nullable()->default('publish');
                    }
                }
                if (! Schema::hasColumn('knowledge_article', 'post_modified')) {
                    $table->dateTime('post_modified')->nullable();
                }
                if (! Schema::hasColumn('knowledge_article', 'category')) {
                    $table->dropColumn('category')->nullable();
                }
                if (! Schema::hasColumn('knowledge_article', 'order')) {
                    $table->integer('order', 10)->nullable()->default('0');
                }
                if (Schema::hasColumn('knowledge_article', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('knowledge_article', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}