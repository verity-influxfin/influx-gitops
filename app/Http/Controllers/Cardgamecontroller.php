<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Cardgamecontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
    }

    public function setGamePrize(Request $request)
    {
        $this->inputs = $request->all();
        $this->data = '';

        try {
            $prizeList = [
                '恭喜抽中威秀雙人套票一組！' => 35,
                '恭喜抽中Linepoint 100點！' => 35,
                '恭喜抽中Linepoint 50點！' => 35,
                '恭喜抽中Linepoint 10點！' => 35,
                '恭喜抽中Linepoint 5點！' => 35,
                '恭喜抽中新年好話一句！牛轉乾坤行好運' => 35,
                '恭喜抽中新年好話一句！牛年天天開心數鈔票' => 35,
                '恭喜抽中新年好話一句！牛年旺旺來' => 35,
            ];
            $this->inputs['get_prize'] = "fsddsv";
            $this->inputs['created_at'] = time();
            $exception = DB::transaction(function () {
                $id = DB::table('cardgame')->insertGetId($this->inputs);
                $this->data = ['prize' => '??'];
            }, 5);
            return response()->json($this->data, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getGreetingData(Request $request)
    {
        $input = $request->all();

        $id = base64_decode($input['token']);
        $result = DB::table('cardgame')->select('*')->where('ID', '=', $id)->first();

        return response()->json($result, 200);
    }

    public function getAns(Request $request)
    {
        $input = $request->all();

        $result = [
            'ans' => $input['qnum'] == 7 && $input['qans'] == 'A' || $input['qnum'] != 7 && $input['qans'] == 'B' ? 0 : 1
        ];
        return response()->json($result, 200);
    }
}
