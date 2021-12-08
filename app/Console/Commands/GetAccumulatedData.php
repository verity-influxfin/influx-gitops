<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GetAccumulatedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accumulatedData:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得顯示在首頁的「累積註冊用戶數」、「累積放款金額數」、「累積成交筆數的資料數」';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //累積註冊用戶
        $user_register_count = Http::get(env('API_URL') . 'user/member_count')->json();
        //累積媒合金額
        $target_loan_sum = Http::get(env('API_URL') . 'target/total_loan_amount')->json();
        //累積成交筆數
        $target_transaction_count = Http::get(env('API_URL') . 'target/transaction_count')->json();

        DB::table('count')->insert([
            'transactionCount' => $target_transaction_count['data']['count'] ?? 0,
            'memberCount' => $user_register_count['data']['count'] ?? 0,
            'totalLoanAmount' => $target_loan_sum['data']['amount'] ?? 0,
            'created_at' => date("Y-m-d H:i:s", strtotime("now")),
            'updated_at' => date("Y-m-d H:i:s", strtotime("now")),
        ]);

        return Command::SUCCESS;
    }
}
