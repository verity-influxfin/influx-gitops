<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StatementExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $this->apiGetway = config('api.apiGetway');
    }

    public function getMyRepayment(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'repayment/dashboard" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRepaymentList(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'repayment/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getNotification(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'notification/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getDetail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'repayment/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }
    public function getTansactionDetails(Request $request)
    {
        $this->validate($request, [
            'isInvest' => 'required|boolean',
        ]);

        $function = $request->post('isInvest') ? 'recoveries' : 'repayment';

        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . $function . '/passbook" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function read(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'notification/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function allRead(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'notification/readall" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getMyInvestment(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'recoveries/dashboard" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesList(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'recoveries/list" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesFinished(Request $request)
    {
        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'recoveries/finish" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }

    public function getRecoveriesInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . 'recoveries/info/' . $request->post('id') . '" -H "' . "request_token:" . Session::get('token') . '"');
        $data = json_decode($curlScrapedPage, true);

        return response()->json($data, $data['result'] === "SUCCESS" ? 200 : 400);
    }


    public function downloadStatement(Request $request)
    {
        $input = $request->all();
        $function = $input['isInvest'] === '1' ? 'recoveries' : 'repayment';

        $curlScrapedPage = shell_exec('curl -k -X GET "' . $this->apiGetway . $function . '/passbook" -H "' . "request_token:" . Session::get('token') . '"');
        $result = json_decode($curlScrapedPage, true);

        $data = [[
            '科目',
            '現金流量',
            '虛擬帳戶餘額',
            '帳務日期'
        ]];
        $i = 1;

        foreach ($result['data']['list'] as $row) {
            if ($input['start'] <= $row['created_at'] . '000' && $row['created_at'] . '000' <= $input['end']) {
                $data[$i]['remark'] = $row['remark'];
                $data[$i]['amount'] = $row['amount'];
                $data[$i]['bank_amount'] = $row['bank_amount'];
                $data[$i]['tx_datetime'] = $row['tx_datetime'];
                $i++;
            }
        }
        return Excel::download(new StatementExport($data), '對帳單.xlsx');
    }
}
