<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Membercentrecontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiGetway;

    public function __construct()
    {
        $this->apiGetway = config('api.developer');
    }

    public function getMyRepayment(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'repayment/dashboard" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRepaymentList(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'repayment/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getNotification(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'notification/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getDetail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'repayment/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }
    public function getTansactionDetails(Request $request)
    {
        $this->validate($request, [
            'isInvest' => 'required|boolean',
        ]);

        $function = $request->post('isInvest') ? 'recoveries' : 'repayment';

        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . $function . '/passbook" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function read(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'notification/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function allRead(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'notification/readall" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getMyInvestment(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'recoveries/dashboard" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesList(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'recoveries/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesFinished(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'recoveries/finish" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $curlScrapedPage = shell_exec('curl -X GET "' . $this->apiGetway . 'recoveries/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }
}
