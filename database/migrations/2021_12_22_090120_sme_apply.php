<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SmeApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sme_apply')) {
            Schema::create('sme_apply', function (Blueprint $table) {
                $table->id();
                $table->string('tax_id', 10)->nullable(false)->comment('統一編號');
                $table->string('company_name', 30)->nullable(false)->comment('公司名稱');
                $table->string('contact_person', 30)->nullable()->comment('聯絡人');
                $table->string('contact_phone', 30)->nullable()->comment('連絡電話');
                $table->string('email', 255)->nullable()->comment('電子信箱');
                $table->tinyInteger('credits')->default(0)->comment('需求額度');
                $table->timestamps();
            });

            DB::statement("ALTER TABLE `sme_apply` COMMENT '微企e秒貸申辦表單'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sme_apply');
    }
}
