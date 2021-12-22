<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SmeConsult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sme_consult')) {
            Schema::create('sme_consult', function (Blueprint $table) {
                $table->id();
                $table->string('tax_id', 10)->nullable(false)->comment('統一編號');
                $table->string('company_name', 30)->nullable(false)->comment('公司名稱');
                $table->tinyInteger('operating_difficulty')->default(0)->comment('目前營運上遇到的困難');
                $table->tinyInteger('funds_purpose')->default(0)->comment('公司資金用途');
                $table->tinyInteger('financing_difficulty')->default(0)->comment('融資時最常遇到的困難');
                $table->string('contact_person', 30)->nullable()->comment('聯絡人');
                $table->string('contact_phone', 30)->nullable()->comment('連絡電話');
                $table->string('email', 255)->nullable()->comment('電子信箱');
                $table->timestamps();
            });

            DB::statement("ALTER TABLE `sme_consult` COMMENT '微企e秒貸諮詢表單'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sme_consult');
    }
}
