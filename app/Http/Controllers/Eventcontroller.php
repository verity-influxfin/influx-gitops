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
            $curlCountDownload = shell_exec('curl -X POST "' . $this->apiGetway . 'article/countdownload" -d "' . $params . '"');
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
            'code' => 'required|digits:6',
            'email' => 'required|email',
            'promo' => 'required'
        ]);

        $input = $request->all();

        $promo = $input['promo'];
        $email = $input['email'];

        $postData = [
            'promote_code'    => $input['promo'],
            'phone'           => $input['phone'],
            'password'        => $input['password'],
            'code'            => $input['code'],
            'investor'        => 0,
        ];

        $params = http_build_query($postData);

        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . 'user/register" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        if ($data['result'] === "SUCCESS") {
            $registerData = [
                'phone' => $input['phone'],
                'promo' => $promo,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s'),
                'created_ip' => $_SERVER['REMOTE_ADDR'],
            ];

            DB::table('event_users')->insert($registerData);
        }

        return response()->json("", $data['result'] === "SUCCESS" ? 200 : 400);
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
