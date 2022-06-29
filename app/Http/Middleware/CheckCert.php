<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class CheckCert
{
    // 徵信項列表
    private $cert_list = [
        'identity' => ['cert_id' => 1]
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $cert_alias = '')
    {
        if (empty($cert_alias)) {
            $cert_alias = $request->route('cert_alias');
        }

        $response = (new Client())
            ->request('GET', env('API_URL') . 'certification/list', [
                'headers' => [
                    'request_token' => Session::get('token')
                ]
            ])
            ->getBody();
        $response = json_decode($response, true);

        if ($response['result'] == 'ERROR' && !empty($response['error'])) {
            switch ($response['error']) {
                case 100:
                    return $this->_return_invalid('token錯誤', 100);
                case 101:
                    return $this->_return_invalid('帳戶已黑名單', 101);

            }
        }

        if (empty($this->cert_list[$cert_alias]['cert_id'])) {
            return $this->_return_invalid('查無此徵信項', 2001);
        }
        $cert_id = $this->cert_list[$cert_alias]['cert_id'];
        $cert_name = $response['data']['list'][$cert_id]['name'] ?? '認證徵信項';
        $cert_user_status = $response['data']['list'][$cert_id]['user_status'] ?? 0;

        if ($cert_user_status != 1) {
            return $this->_return_invalid($cert_name . '未通過', 2002);
        }

        return $next($request);
    }

    private function _return_invalid($msg, $code): JsonResponse
    {
        return response()->json(['success' => false, 'msg' => $msg, 'error' => $code]);
    }
}
