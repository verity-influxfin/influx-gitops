<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('borrow_report')) {
            Schema::create('borrow_report', function (Blueprint $table) {
                $table->id();
                $table->integer('identity', 11)->comment('身份 1:學生,2:上班族');
                $table->string('name', 20)->comment('姓名');
                $table->string('educational_level', 125)->nullable()->comment('教育程度(上班族必填)');
                $table->string('is_top_enterprises', 125)->nullable()->comment('是否為上市櫃或金融機構、公家機關(上班族必填)');
                $table->string('insurance_salary', 125)->nullable()->comment('投保薪資(上班族必填)');
                $table->string('debt_amount', 125)->nullable()->comment('銀行貸款餘額(上班族必填)');
                $table->string('monthly_repayment', 125)->nullable()->comment('每月攤還金額(上班族必填)');
                $table->string('creditcard_quota', 125)->nullable()->comment('信用卡總額度(上班族必填)');
                $table->string('creditcard_bill', 125)->nullable()->comment('近一個月信用卡帳單總金額(上班族必填)');
                $table->string('school_name', 125)->nullable()->comment('就讀學校(學生必填)');
                $table->string('department', 125)->nullable()->comment('學校科系(學生必填)');
                $table->string('is_student_loan', 125)->nullable()->comment('是否有學貸(學生必填) 是:True,否:False');
                $table->string('is_part_time_job', 125)->nullable()->comment('是否有打工兼職(學生必填) 是:True,否:False');
                $table->string('monthly_economy', 125)->nullable()->comment('每月經濟(學生必填)');
                $table->string('amount', 125)->nullable()->comment('額度');
                $table->string('rate', 125)->nullable()->comment('利率');
                $table->string('platform_fee', 125)->nullable()->comment('手續費');
                $table->string('repayment', 125)->nullable()->comment('每期攤還金額');
                $table->string('total_point', 125)->nullable()->comment('試算總分');
                $table->timestamps();
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
        Schema::dropIfExists('borrow_report');
    }
}
