<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $routerDonateAnonymous = 'user/donate_anonymous';

    // product/applylist
    public function getApplyList()
    {
        $return = $this->_connectDeus('GET', 'product/applylist', []);
        return response()->json($return['data'], $return['status']);
    }

    // product/applyinfo
    public function getApplyInfo(Request $request)
    {
        $inputs = $request->all();
        // echo Session::get('token');
        $return = $this->_connectDeus('GET', 'product/applyinfo/'.$inputs['id'], []);
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
        if ($this->_isVisitorLimited($cacheKey)) {
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
                [
                    'headers' => [
                        'request_token' => Session::get('token')
                    ]
                ],
                $this->_parseRequestPayload($method, $inputs)
            );
        } catch (Exception $e) {
            return ['status' => 500, 'data' => []];
        }

        return $this->_parseDeusResponse(json_decode($res->getBody(), TRUE));
    }

    private function _parseRequestPayload($method, $inputs)
    {
        switch ($method) {
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

        if (
            isset($responseData['result']) &&
            !isset($responseData['error']) &&
            $responseData['result'] === 'SUCCESS'
        ) {
            $returnData['status'] = 200;
            $returnData['data'] = [
                'data' => $responseData['data']
            ];
        } elseif (isset($responseData['error'])) {
            $returnData['status'] = 400;
            $returnData['data'] = [
                'error' => $responseData['error'],
            ];
        }

        return $returnData;
    }

}
