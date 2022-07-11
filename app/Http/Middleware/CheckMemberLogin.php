<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class CheckMemberLogin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session_token = Session::get('token');
        if (empty($session_token)) {
            return $this->_return_invalid('token錯誤', 100);
        }

        $response = (new Client())
            ->request('GET', env('API_URL') . 'user/info', [
                'headers' => [
                    'request_token' => $session_token
                ]
            ])
            ->getBody();
        $response = json_decode($response, true);

        if ($response['result'] != 'SUCCESS') {
            $response['error'] = $response['error'] ?? '';
            switch ($response['error']) {
                case 100:
                    return $this->_return_invalid('token錯誤', 100);
                case 101:
                    return $this->_return_invalid('帳戶已黑名單', 101);
                default:
                    return $this->_return_invalid('查無用戶訊息');
            }
        }

        return $next($request);
    }

    private function _return_invalid($msg, $code = ''): JsonResponse
    {
        return response()->json(['success' => false, 'msg' => $msg, 'error' => $code]);
    }
}
