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
                0 => [
                    'prize' => '威秀雙人套票一組',
                    'rotate' => '152',
                ],
                1 => [
                    'prize' => 'Linepoint 100點',
                    'rotate' => '69',
                ],
                2 => [
                    'prize' => 'Linepoint 50點',
                    'rotate' => '232',
                ],
                3 => [
                    'prize' => 'Linepoint 10點',
                    'rotate' => '340',
                ],
                4 => [
                    'prize' => 'Linepoint 5點',
                    'rotate' => '259',
                ],
                5 => [
                    'prize' => '新年好話一句！牛轉乾坤行好運',
                    'rotate' => '163',
                ],
                6 => [
                    'prize' => '新年好話一句！牛年天天開心數鈔票',
                    'rotate' => '123',
                ],
                7 => [
                    'prize' => '新年好話一句！牛年旺旺來',
                    'rotate' => '49',
                ],
            ];

            $result = DB::table('cardgame')->select('*')->where('user_id', '=', $this->inputs['user_id'])->first();

            if(!$result){
                $rand = rand(1,100);
                if($rand == 0){
                    $prize = $prizeList[0];
                }elseif($rand >= 1 && $rand <= 3){
                    $prize = $prizeList[1];
                }elseif($rand >= 4 && $rand <= 8){
                    $prize = $prizeList[2];
                }elseif($rand >= 9 && $rand <= 20){
                    $prize = $prizeList[3];
                }elseif($rand >= 21 && $rand <= 40){
                    $prize = $prizeList[4];
                }elseif($rand >= 41 && $rand <= 60){
                    $prize = $prizeList[5];
                }elseif($rand >= 61 && $rand <= 80){
                    $prize = $prizeList[6];
                }elseif($rand >= 81 && $rand <= 100){
                    $prize = $prizeList[7];
                }

                $this->inputs['get_prize'] = $prize['prize'];
                $this->inputs['created_at'] = time();
                $exception = DB::transaction(function () {
                    $id = DB::table('cardgame')->insertGetId($this->inputs);
                }, 5);
                $this->data = [
                    'prize' => $prize['prize'],
                    'rotate' => $prize['rotate']
                ];
                return response()->json($this->data, 200);
            }
            return response()->json($result->get_prize, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getData(Request $request)
    {
        $input = $request->all();

        $id = $input['user_id'];
        $result = DB::table('cardgame')->select('*')->where('user_id', '=', $id)->first();

        return response()->json($result?true:false, 200);
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
