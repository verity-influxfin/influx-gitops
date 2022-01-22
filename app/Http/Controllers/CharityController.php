<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CharityEvent;

class CharityController extends Controller
{
    /**
     * 取得捐款資料
     */
    public function getDonation()
    {
        ini_set('max_execution_time', 0);
        date_default_timezone_set('Asia/Taipei');
        header('Cache-Control: no-cache');
        header('Content-Type: text/event-stream');
        header('X-Accel-Buffering: no');

        echo "event: ping\n";
        echo "data: \n\n";

        foreach (range(1,5) as $times)
        {
            // 排名
            echo "event: ranking_data\n";
            echo sprintf("data: %s\n\n", 
                CharityEvent::where('type', 0)->orderBy('amount', 'DESC')->take(10)->get()->toJson()
            );

            // 即時
            echo "event: realtime_data\n";
            echo sprintf("data: %s\n\n", 
                CharityEvent::where('type', 1)
                    ->where('amount', '>', 10000)
                    ->orderBy('amount', 'DESC')
                    ->take(50)
                    ->get()
                    ->toJson()
            );
            flush();
            sleep(1);
        }

        echo "event: pong\n";
        echo "data: \n\n";
    }
}