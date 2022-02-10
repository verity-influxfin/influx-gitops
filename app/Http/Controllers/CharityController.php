<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CharityEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        $stop_at = time() + 30;

        while(time() < $stop_at)
        {
            // 撈主系統資料寫進DB
            $this->_insertAllList();

            // 排名
            echo "event: ranking_data\n";
            echo sprintf("data: %s\n\n",
                CharityEvent::orderBy('amount', 'DESC')->take(5)->get()->toJson()
            );

            // 即時
            echo "event: realtime_data\n";
            echo sprintf("data: %s\n\n",
                CharityEvent::orderBy('amount', 'DESC')
                    ->get()
                    ->toJson()
            );
            flush();
            sleep(1);
        }

        echo "event: pong\n";
        echo "data: \n\n";
    }

    private function _insertAllList()
    {
        $this->_insertAppList();
        return $this->_insertManualList();
    }

    private function _insertAppList()
    {
        $prefix = 'c'; // APP自動寫入主系統

        $max_id = DB::table('charity_event')
            ->select(DB::raw('MAX(id) AS id'))
            ->where('prefix', $prefix)
            ->get()
            ->first();
        $response = Http::get(env('API_URL').'website/ntu_donation_list?max='.$max_id->id ?? 0)->json();
        if (!isset($response['data'])) {
            return;
        }

        foreach ($response['data'] as $value) {
            $data = json_decode($value['data'], TRUE);

            DB::table('charity_event')->insert([
                'id' => $value['id'],
                'name' => mb_substr($data['name'], 0, 1, 'utf8').'OO',
                'amount' => $value['amount'],
                'type' => 0,
                'weight' => 10,
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'prefix' => $prefix
            ]);
        }
    }

    private function _insertManualList()
    {
        $prefix = 'n'; // 人工手動寫入主系統

        $max_id = DB::table('charity_event')
            ->select(DB::raw('MAX(id) AS id'))
            ->where('prefix', $prefix)
            ->get()
            ->first();
        $response = Http::get(env('API_URL').'website/ntu_donation_list_manual?max='.$max_id->id ?? 0)->json();
        if (!isset($response['data'])) {
            return;
        }

        foreach ($response['data'] as $value) {
            DB::table('charity_event')->insert([
                'id' => $value['id'],
                'name' => mb_substr($value['user_name'], 0, 1, 'utf8').'OO',
                'amount' => $value['amount'],
                'type' => $value['type'] ? 1 : 0,
                'weight' => $value['weight'],
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'prefix' => $prefix
            ]);
        }
    }
}
