<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CharityEvent;
use Cache;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CharityController extends Controller
{

    private $routerDonateAnonymous = 'user/donate_anonymous';

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

        while (time() < $stop_at)
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
        $response = Http::get(env('API_URL') . 'website/ntu_donation_list?max=' . $max_id->id ?? 0)->json();
        if ( ! isset($response['data']))
        {
            return;
        }

        foreach ($response['data'] as $value)
        {
            $data = json_decode($value['data'], TRUE);

            DB::table('charity_event')->insert([
                'id' => $value['id'],
                'name' => mb_substr($data['name'], 0, 1, 'utf8') . 'OO',
                'amount' => $value['amount'],
                'type' => 0,
                'weight' => 10,
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'prefix' => $prefix,
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
        $response = Http::get(env('API_URL') . 'website/ntu_donation_list_manual?max=' . $max_id->id ?? 0)->json();
        if ( ! isset($response['data']))
        {
            return;
        }

        foreach ($response['data'] as $value)
        {
            DB::table('charity_event')->insert([
                'id' => $value['id'],
                'name' => mb_substr($value['user_name'], 0, 1, 'utf8') . 'OO',
                'amount' => $value['amount'],
                'type' => $value['type'] ? 1 : 0,
                'weight' => $value['weight'],
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'prefix' => $prefix,
            ]);
        }
    }

    // 遊客捐款
    public function visitorDonate(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|integer',
        ]);

        $inputs = $request->all();
        $inputs['source'] = 1; // 捐款來源為官網

        $cacheKey = $request->ip() ?? '127.0.0.1';
        if ($this->_isVisitorLimited($cacheKey))
        {
            return response()->json([], 503);
        }

        $return = $this->_connectDeus('POST', $this->routerDonateAnonymous, $inputs);
        return response()->json($return['data'], $return['status']);
    }

    // 查詢捐款紀錄
    public function visitorSearch(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|integer',
            'last5' => 'required|size:5',
        ]);

        $inputs = $request->all();

        $cacheKey = $request->ip() ?? '127.0.0.1';
        if ($this->_isVisitorLimited($cacheKey))
        {
            return response()->json([], 503);
        }

        $return = $this->_connectDeus('GET', $this->routerDonateAnonymous, $inputs);
        return response()->json($return['data'], $return['status']);
    }

    private function _connectDeus($method, $router, $inputs)
    {
        try {
            $client = new Client();
            $res = $client->request(
                $method,
                env('API_URL') . $router,
                $this->_parseRequestPayload($method, $inputs)
            );
        }
        catch (Exception $e)
        {
            return ['status' => 500, 'data' => []];
        }

        return $this->_parseDeusResponse(json_decode($res->getBody(), TRUE));
    }

    private function _parseRequestPayload($method, $inputs)
    {
        switch ($method)
        {
        case 'GET':
            $payload = ['query' => $inputs];
            break;
        case 'POST':
            $payload = ['form_params' => $inputs];
            break;
        default:
            $payload = [];
            break;
        }

        return $payload;
    }

    private function _parseDeusResponse($responseData)
    {
        $returnData = [
            'status' => 500,
            'data' => [],
        ];

        if (isset($responseData['result']) &&
            ! isset($responseData['error']) &&
            $responseData['result'] === 'SUCCESS')
        {
            $returnData['status'] = 200;
            $returnData['data'] = $responseData['data'];
        }
        elseif (isset($responseData['error']))
        {
            $returnData['status'] = 400;
            $returnData['data'] = [
                'error' => $responseData['error'],
                'msg' => $responseData['data']['msg'],
            ];
        }

        return $returnData;
    }

    private function _isVisitorLimited($cacheKey)
    {
        if ( ! Cache::has($cacheKey))
        {
            Cache::put($cacheKey, 1, 180);
        }

        $value = Cache::get($cacheKey, 1);
        if ($value > 5)
        {
            return TRUE;
        }

        Cache::increment($cacheKey);
        return FALSE;
    }
}
