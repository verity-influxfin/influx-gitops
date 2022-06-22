<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBorrowReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrow_report', function (Blueprint $table) {
            $table->string('contact_time', 125)->nullable()->comment('方便聯繫時間')->after('monthly_economy');
            $table->string('is_contact', 5)->nullable()->comment('一對一專人聯繫服務')->after('monthly_economy');
            $table->text('reason')->nullable()->comment('原因')->after('monthly_economy');
            $table->string('line', 50)->nullable()->comment('Line帳號')->after('monthly_economy');
            $table->string('phone', 10)->nullable()->comment('手機號碼')->after('monthly_economy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_report', function (Blueprint $table) {
            //
        });
    }
}
