<?php

namespace App\Http\Controllers;

use App\Rules\mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Eventcontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiGetway;

    public function __construct()
    {
        $this->apiGetway = config('api.apiGetway');
    }

    public function getNum(Request $request)
    {
        $input = $request->all();

        $downloadNum = 0;
        $userNum = 0;

        if (isset($input['promo']) && $input['promo'] != '') {
            $params = http_build_query($input);
            $curlCountDownload = shell_exec('curl -k -X POST "' . $this->apiGetway . 'article/countdownload" -d "' . $params . '"');
            $downloadNum = json_decode($curlCountDownload, true);
            $userNum = DB::table('event_users')->select('*')->where('promo', '=', $input['promo'])->count();
        }

        $data['downloadNum'] = $downloadNum;
        $data['userNum'] = $userNum;

        return response()->json($data,  200);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:10',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'code' => 'required|digits:6'
        ]);

        $input = $request->all();

        $promo = isset($input['promo']) ? $input['promo'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $promote_info = isset($input['promote_info']) ? json_encode($input['promote_info']) : '';

        $postData = [
            'promote_code'    => $promo,
            'phone'           => $input['phone'],
            'password'        => $input['password'],
            'code'            => $input['code'],
            'investor'        => 0,
        ];

        $params = http_build_query($postData);

        $curlScrapedPage = shell_exec('curl -k -X POST "' . $this->apiGetway . 'user/register" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);
        if ($data['result'] === "SUCCESS") {
            $registerData = [
                'phone' => $input['phone'],
                'promo' => $promo,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
                'created_ip' => $_SERVER['REMOTE_ADDR'],
                'promo_info' => $promote_info
            ];

            DB::table('event_users')->insert($registerData);
        }

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    // for 2023 new year event
    public function newyearRegister(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:10',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'code' => 'required|digits:6'
        ]);

        $input = $request->all();

        $promo = isset($input['promo']) ? $input['promo'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $promote_info = isset($input['promote_info']) ? json_encode($input['promote_info']) : '';

        $postData = [
            'promote_code'    => $promo,
            'phone'           => $input['phone'],
            'password'        => $input['password'],
            'code'            => $input['code'],
            'investor'        => 0,
        ];

        $params = http_build_query($postData);
        $curlScrapedPage = shell_exec('curl -k -X POST "' . $this->apiGetway . 'user/register" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);
        if(!empty($data['data'])){
            $userInfoReq = shell_exec('curl -k -X GET "' . $this->apiGetway . 'user/info" -H "' . "request_token:" . $data['data']['token'] . '"');
            $userInfo = json_decode($userInfoReq, true);
            $newyearObj = [
                'user_id' => $userInfo['data']['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_ip' => $_SERVER['REMOTE_ADDR'],
                // 新戶一定是1
                'prize_status' => 1,
            ];
        }

        if ($data['result'] === "SUCCESS") {
            // 此活動要驗證常用電子信箱認證項目
            $emailReq = shell_exec('curl -k -X POST "' . $this->apiGetway . 'certification/email" -H "' . "request_token:" . $data['data']['token'] . '" -d "email=' . $email . '"');
            $emailResult = json_decode($emailReq, true);
            if($emailResult['result'] === "SUCCESS"){
                $registerData = [
                    'phone' => $input['phone'],
                    'promo' => $promo,
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_ip' => $_SERVER['REMOTE_ADDR'],
                    'promo_info' => $promote_info
                ];
                DB::table('event_users')->insert($registerData);
                DB::table('2023_newyear_event_prize')->insert($newyearObj);
            }
        }

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function newyearLogin(Request $request)
    {
        $this->validate($request, [
            'phone' => ['required', new mobile()],
            'password' => 'required|string|min:6|max:50',
        ]);

        $input = $request->all();
        $phone = $input['phone'];
        $password = $input['password'];
        $email = $input['email'];

        $params = http_build_query([
            'phone' => $phone,
            'password' => $password
        ]);

        $curlScrapedPage = shell_exec('curl -k -X POST "' . $this->apiGetway . 'user/login" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        if ($data['result'] === "SUCCESS") {
            Session::put('token', $data['data']['token']);
            $token = $data['data']['token'];
            $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'user/info" -H "' . "request_token:" . $data['data']['token'] . '"');
            $data = json_decode($curlScrapedPage, true);

            Session::put('userData', $data['data']);
            // 一律設定為非投資人
            Session::put('investor', 0);
            // 登入後發送驗證 不管正確與失敗
            shell_exec('curl -k -X POST "' . $this->apiGetway . 'certification/email" -H "' . "request_token:" . $token . '" -d "email=' . $email . '"');
            $curlIdentity = shell_exec('curl -k -X GET "' . $this->apiGetway . 'certification/identity" -H "' . "request_token:" . $token . '"');
            $identity_data = json_decode($curlIdentity, true);
            // 設定前端用中獎變數
            $prize = 0;
            // 設定 2023_newyear_event_prize 用 中獎變數
            $prize_status = 0;
            if($identity_data['result'] == "SUCCESS" && $identity_data['data']['status'] == 0){
                // 未完成實名認證 所以中獎
                $prize = 1;
                $prize_status = 2;
            }
            $dbData = DB::table('2023_newyear_event_prize')->where('user_id', $data['data']['id'])->first();
            if($dbData){
                // 已參加過活動
                $prize = 2;
            } else {
                DB::table('2023_newyear_event_prize')->insert([
                    'user_id' => $data['data']['id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_ip' => $_SERVER['REMOTE_ADDR'],
                    'prize_status' => $prize_status,
                ]);
            }
            return response()->json([
                'id' => $data['data']['id'],
                'name' => $data['data']['name'],
                'picture' => $data['data']['picture'],
                'prize' => $prize,
                'investor' => 0
            ], 200);
        } else {
            return response()->json($data, 400);
        }
    }

	public function bankEvent(Request $request)
	{
		$input = $request->all();

		if(empty($input['phone']) || empty($input['name']) || empty($input['email']) || empty($input['page_from'])){
			response()->json('{"response":"error","description":"parameter is null"}',  401);
		}

		$eventData = [
			'phone' => $input['phone'],
			'name' => $input['name'],
			'email' => $input['email'],
			'page_from' => $input['page_from'],
			'created_at' => date('Y-m-d H:i:s', strtotime('+8 hours')),
			'created_ip' => $_SERVER['REMOTE_ADDR'],
		];

		DB::table('event_banks')->insert($eventData);
		return response()->json('{"response":"success"}',  200);
	}
}
