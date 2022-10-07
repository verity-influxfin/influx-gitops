<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskReportInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_report_info', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable(false)->comment('年份');
            $table->integer('month')->nullable(false)->comment('月份');
            $table->double('yearly_rate_of_return')->nullable(false)->comment('平均年化報酬率');
            $table->json('this_month_apply')->nullable(false)->comment('本月案件數');
            $table->json('total_apply')->nullable(false)->comment('累計案件數');
            $table->json('delay_rate')->nullable(false)->comment('逾期概況');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risk_report_info');
    }
}
