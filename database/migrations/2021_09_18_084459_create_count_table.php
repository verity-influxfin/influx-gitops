<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('count')) {
            Schema::create('count', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('transactionCount')->unsigned()->comment('成交筆數');
                $table->bigInteger('memberCount')->unsigned()->comment('會員數');
                $table->bigInteger('totalLoanAmount')->unsigned()->comment('累積放款金額');
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
        Schema::dropIfExists('count');
    }
}
