<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                if (Schema::hasColumn('media', 'ID')) {
                    DB::statement("ALTER TABLE `media` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('media', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('media', 'updated_at')){
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
        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                if (Schema::hasColumn('media', 'id')) {
                    DB::statement("ALTER TABLE `media` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('media', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('media', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
