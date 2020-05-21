<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Accountcontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiGetway;

    public function __construct()
    {
        $this->apiGetway = config('api.developer');
    }

    public function getTerms(Request $request)
    {
        $this->validate($request, [
            'termsType' => 'required|string|in:privacy_policy,user'
        ]);

        $termsType = $request->post('termsType');

        $curlScrapedPage = shell_exec("curl " . $this->apiGetway . "agreement/info/$termsType");

        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : $data['error']);
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'tax_id' => 'sometimes|required|digits:8',
            'phone' => 'required|digits:10',
            'password' => 'required|string|min:6|max:50'
        ], [
            'tax_id.required' => '請輸入統編',
            'phone.required' => '請輸入帳號',
            'password.required' => '請輸入密碼',
            'tax_id.digits' => '統編長度不符',
            'phone.digits' => '手機長度不符',
            'min' => '密碼長度不符',
            'max' => '密碼長度不符'
        ]);

        $input = $request->all();

        $params = http_build_query($input);

        $function = array_key_exists('tax_id', $input) ? 'judicialperson' : 'user';
        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . $function . '/login" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        if ($data['result'] === "SUCCESS") {
            Session::put('token',$data['data']['token']);

            $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'user/info" -H "' . "request_token:" . $data['data']['token'] . '"');
            $data = json_decode($curlScrapedPage, true);

            Session::put('userData',$data['data']);

            return response()->json([
                'id' => $data['data']['id'],
                'name' => $data['data']['name'],
                'picture' => $data['data']['picture']
            ], 200);
        } else {
            return response()->json($data, 400);
        }
    }
    public function logout(Request $request)
    {
        Session::forget('token');
        Session::forget('userData');

        return response()->json('sucess');
    }

    public function getCaptcha(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:10',
            'type' => 'required|string|in:smsloginphone,registerphone'
        ]);

        $input = $request->all();
        $function = $input['type'];
        unset($input['type']);
        $params = http_build_query($input);

        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . 'user/' . $function . '" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:10',
            'new_password' => 'required|min:6|max:50|confirmed|alpha_dash',
            'new_password_confirmation' => 'required|min:6|max:50|alpha_dash',
            'code' => 'required|digits:6'
        ], [
            'phone.required' => '請輸入手機號碼',
            'new_password.required' => '請輸入新密碼',
            'new_password_confirmation.required' => '請輸入確認新密碼',
            'code.required' => '請輸入驗證碼',
            'phone.digits' => '手機長度不符',
            'code.digits' => '驗證碼長度不符',
            'min' => '密碼長度不符',
            'max' => '密碼長度不符',
            'confirmed' => '密碼不一致'
        ]);

        $input = $request->all();

        unset($input['new_password_confirmation']);
        $params = http_build_query($input);

        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . 'user/forgotpw" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function doRegister(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:10',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'code' => 'required|digits:6'
        ], [
            'phone.required' => '請輸入手機號碼',
            'new_password.required' => '請輸入密碼',
            'new_password_confirmation.required' => '請輸入確認密碼',
            'code.required' => '請輸入驗證碼',
            'phone.digits' => '手機長度不符',
            'code.digits' => '驗證碼長度不符',
            'min' => '密碼長度不符',
            'max' => '密碼長度不符',
            'confirmed' => '密碼不一致'
        ]);

        $input = $request->all();

        unset($input['confirmPassword']);
        $params = http_build_query($input);

        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . 'user/register" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }
}
