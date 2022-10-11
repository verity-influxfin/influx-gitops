<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ERP 帳務系統 library
 */
class Erp_lib
{

    /**
     * 加密方式
     *
     * @var        string
     */
    public $method = 'aes-128-cbc';

    /**
     * AES 資訊
     *
     * @var        array
     */
    private $_aes = NULL;

    /**
     * 建構子
     *
     * @param      array  $params  參數
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    function __construct(array $params=[])
    {
        $CI =& get_instance();
        $CI->load->helper('string');

        $this->host = $params['host'] ?? '';

        $this->hostname = strtolower(implode('_', [
            getenv('APP_NAME') ?: 'deus',
            ENVIRONMENT
        ]));
    }

    /**
     * 取得組合後 URL 字串
     *
     * @param      string  $uri    URI 片段
     *
     * @return     string          URL 字串
     * 
     * @created_at                2021-09-14
     * @created_by                Jack
     */
    public function get_url(string $uri)
    {
        return reduce_double_slashes(implode('/', [
            $this->host, $uri
        ]));
    }

    /**
     * 取得交易 API 目標 URL
     *
     * @param      string  $scenario  交易情境
     *
     * @return     string             交易 API URL
     * 
     * @created_at                    2021-09-14
     * @created_by                    Jack
     */
    public function get_transaction_target(string $scenario)
    {
        return $this->get_url('webhook/v1/transaction/' . $scenario);
    }

    /**
     * 通知交易 API
     *
     * @param      string               $scenario  交易情境
     * @param      array                $data      API 資料
     *
     * @return     \GuzzleHttp\Promise             Guzzle Promise
     * 
     * @created_at                                 2021-09-14
     * @created_by                                 Jack
     */
    public function transaction(string $scenario, array $data)
    {
        return utility('portman')->post_async(
            $this->get_transaction_target($scenario),
            [
                'headers' => [
                    'X-Session-ID' => $this->session_id,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => sprintf('data=%s', urlencode($this->_encrypt_data($data)))
            ]
        );
    }

    /**
     * 取得通知結果
     *
     * @param      Iterable  $promises  發送 promise
     * @return     bool                 執行結果
     *
     * @created_at                      2022-02-12
     * @created_by                      Jack
     */
    public function get_result(Iterable $promises)
    {
        $CI =& get_instance();
        $CI->load->library('platelet_lib');

        try
        {

            // 取得 API 回應
            foreach (utility('portman')->send_async($promises) as $resp)
            {
                if (! empty($contents = $resp->getBody()->getContents()))
                {

                    // 血小板通知
                    $CI->platelet_lib->info($contents);
                }
            }
        }
        catch (Throwable $e)
        {

            // 血小板通知
            $CI->platelet_lib->error($e->getMessage());
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 取得報表
     *
     * @param      string  $report_type  報表名稱
     * @param      array   $data         請求資料
     *
     * @return     array                 報表資料
     * 
     * @created_at                       2021-08-04
     * @created_by                       Jack
     * @updated_at                       2022-01-28
     * @updated_by                       Jack
     */
    public function get_report(string $report_type, array $data=[])
    {
        $url = reduce_double_slashes(implode('/', [
            $this->host,
            '/webhook/v1/report/',
            $report_type,
        ]));

        if (! empty($data))
        {
            $url .= '?' . http_build_query([
                'data' => $this->_encrypt_data($data)
            ]);
        }

        $response = json_decode($resp_raw = curl_get(
            $url,
            [],
            [ 'X-Session-ID: '. $this->session_id ]
        ), TRUE);

        if ($response['success'] ?? FALSE)
        {
            return $response['data'];
        }
        return [];
    }

    /**
     * 取得報表
     *
     * @param      string  $report_type  報表名稱
     * @param      array   $data         請求資料
     *
     * @return     array                 報表資料
     * 
     * @created_at                       2021-08-04
     * @created_by                       Jack
     * @updated_at                       2022-01-28
     * @updated_by                       Jack
     */
    public function get_p2ploan(string $report_type, array $data=[])
    {
        $url = reduce_double_slashes(implode('/', [
            $this->host,
            '/webhook/v1/p2p_loan/',
            $report_type,
        ]));

        if (! empty($data))
        {
            $url .= '?' . http_build_query([
                'data' => $this->_encrypt_data($data)
            ]);
        }

        $response = json_decode(curl_get(
            $url,
            [],
            [ 'X-Session-ID: '. $this->session_id ]
        ), TRUE);

        if ($response['success'] ?? FALSE)
        {
            return $response['data'];
        }
        return [];
    }

    /**
     * 取得 AES 資料
     *
     * @return     array    AES    資料陣列
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     * @created_at                 2021-09-16
     * @created_by                 Jack
     */
    public function get_aes()
    {
        if (empty($this->_aes))
        {
            // 有檔案則讀檔
            if (is_readable($filepath = __DIR__ . '/aes.json'))
            {
                $this->_aes = json_decode(file_get_contents($filepath), TRUE);
            }

            if (empty($this->_aes))
            {

                // 產生 secret_key 並呼叫 ERP 取得 session_id
                $this->_aes = [
                    'secret_key' => $secret_key = substr(md5(rand()), 0, 16),
                    'session_id' => $this->_request_session_id($secret_key),
                ];

                // 寫入檔案
                $file = fopen($filepath, 'w');
                fwrite($file, json_encode($this->_aes));
                fclose($file);
            }
        }
        return $this->_aes;
    }

    /**
     * 發送請求取得 session ID
     *
     * @param      string  $secret_key  AES 密鑰
     *
     * @return     string               session ID
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    public function _request_session_id(string $secret_key)
    {

        // 取得 ERP RSA 公鑰
        $rsa_pub = curl_get($this->host . '/rsa_key.pub');

        // RSA 公鑰加密 {hostname}.{secret_key}
        openssl_public_encrypt(
            $this->hostname . '.' . $secret_key,
            $encrypted_msg,
            curl_get($this->host . '/rsa_key.pub')
        );

        // 取得 session_id
        $response = json_decode(curl_get(
            $this->host . '/cert/v1/session/registry',
            [ 'data' => base64_encode($encrypted_msg) ]
        ), TRUE);

        if ( ! empty($retval = $response['data']['session_id']))
        {
            return $retval;
        }
        return NULL;
    }

    /**
     * 取得 session_id
     *
     * @return     string          session ID
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    public function get_session_id()
    {
        return $this->aes['session_id'] ?? NULL;
    }

    /**
     * 取得 AES 密鑰
     *
     * @return     string          AES 密鑰
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    public function get_secret_key()
    {
        return $this->aes['secret_key'] ?? NULL;
    }

    /**
     * __get magic method
     *
     * @param      string  $name   屬性名稱
     * 
     * @return     any
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    public function __get(string $name)
    {
        if (is_callable([$this, 'get_' . $name]))
        {
            return call_user_func([$this, 'get_' . $name]);
        }
    }

    /**
     * AES 解密
     *
     * @param      string  $encrypted_msg  已加密資料
     *
     * @return     array                   解密結果陣列
     * 
     * @created_at                         2021-08-04
     * @created_by                         Jack
     */
    public function decrypt(string $encrypted_msg)
    {
        $iv = openssl_random_pseudo_bytes($iv_len = openssl_cipher_iv_length($this->method));
        $decrypted = openssl_decrypt($encrypted_msg, $this->method, $this->secret_key, 0);
        return json_decode(urldecode(substr($decrypted, $iv_len, strlen($decrypted))), TRUE);
    }

    /**
     * AES 加密
     *
     * @param      array   $data   加密資料陣列
     * 
     * @return     string          加密結果
     * 
     * @created_at                 2021-08-04
     * @created_by                 Jack
     */
    private function _encrypt_data(array $data)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        return openssl_encrypt($iv . json_encode($data), $this->method, $this->secret_key, 0, $iv);
    }
}