<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBorrowReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('borrow_report')) {
            Schema::table('borrow_report', function (Blueprint $table) {
                if (Schema::hasColumn('borrow_report', 'ID')) {
                    DB::statement("ALTER TABLE `borrow_report` CHANGE `ID` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
                }
                if (! Schema::hasColumn('borrow_report', 'updated_at')){
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
        if (Schema::hasTable('borrow_report')) {
            Schema::table('borrow_report', function (Blueprint $table) {
                if (Schema::hasColumn('borrow_report', 'id')) {
                    DB::statement("ALTER TABLE `borrow_report` CHANGE `id` `ID` INT(11) NOT NULL AUTO_INCREMENT");
                }
                if (Schema::hasColumn('borrow_report', 'updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
