<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'tax_id' => 'sometimes|required|string',
            'phone' => 'required|string|size:10',
            'password' => 'required|string|min:6|max:50'
        ]);

        $input = $request->all();

        $params = http_build_query($input);

        $function = array_key_exists('tax_id', $input) ? 'judicialperson' : 'user';
        $curlScrapedPage = shell_exec('curl -X POST "' . $this->apiGetway . $function . '/login" -d "' . $params . '"');

        $data = json_decode($curlScrapedPage, true);

        if ($data['result'] === "SUCCESS") {
            $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'user/info" -H "' . "request_token:" . $data['data']['token'] . '"');

            $data = json_decode($curlScrapedPage, true);
            return response()->json($data['data'], 200);
        } else {
            return response()->json($data, 400);
        }
    }

    public function getCaptcha(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string|size:10',
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
            'phone' => 'required|string|size:10',
            'new_password' => 'required|string|min:6|max:50|confirmed',
            'new_password_confirmation' => 'required|string|min:6|max:50',
            'code' => 'required|string|size:6'
        ], [
            'phone.size' => '手機長度不符',
            'code.size' => '驗證碼長度不符',
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
            'phone' => 'required|string|size:10',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50',
            'code' => 'required|string|size:6'
        ], [
            'phone.size' => '手機長度不符',
            'code.size' => '驗證碼長度不符',
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
