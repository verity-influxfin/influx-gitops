<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
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
        $return = $this->_connectDeus('GET', 'product/applyinfo/' . $inputs['id'], []);
        return response()->json($return['data'], $return['status']);
    }

    // certification/judicial_file_upload
    public function postCertFileUpload(Request $request)
    {
        $inputs = $request->all();
        $return = $this->_connectDeus('POST', 'certification/judicial_file_upload', $inputs);
        return response()->json($return['data'], $return['status']);
    }

    // certification/judicial_file_upload
    public function postNaturalFileUpload(Request $request)
    {
        $inputs = $request->all();
        $return = $this->_connectDeus('POST', 'certification/natural_file_upload', $inputs);
        return response()->json($return['data'], $return['status']);
    }

    // certification/profile
    public function postCertificationProfile(Request $request)
    {
        $inputs = $request->all();
        $return = $this->_connectDeus('POST', 'certification/profile', $inputs);
        return response()->json($return['data'], $return['status']);
    }

    // certification/email
    public function postCertificationEmail(Request $request)
    {
        $inputs = $request->all();
        $return = $this->_connectDeus('POST', 'certification/email', $inputs);
        return response()->json($return['data'], $return['status']);
    }

    // certification/profilejudicial
    public function postCertificationProfilejudicial(Request $request)
    {
        $inputs = $request->all();
        $return = $this->_connectDeus('POST', 'certification/profilejudicial', $inputs);
        return response()->json($return['data'], $return['status']);
    }

    public function postUploadPdf()
    {
        $return = $this->_uploadFile('user/upload_pdf', $_FILES, 'pdf');
        return response()->json($return['data'], $return['status']);
    }

    public function postUpload()
    {
        $return = $this->_uploadFile('user/upload', $_FILES, 'image');
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

    private function _uploadFile($path, $file, $type)
    {
        try {
            $client = new Client();

            if ($file[$type]['type'] != 'application/pdf') {
                return ['status' => 400, 'data' => ['error' => 200, 'msg' => '格式錯誤']];
            }

            $res = $client->request('POST', env('API_URL') . $path, [
                'headers' => [
                    'request_token' => Session::get('token')
                ],
                'multipart' => [
                    [
                        'name'     => $type,
                        'contents' => Psr7\Utils::tryFopen($file[$type]['tmp_name'], 'r'),
                        'filename' => $file[$type]['name']
                    ],
                ]
            ]);
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
