<?php defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use phpseclib3\Crypt\PublicKeyLoader;

class Ocr2_lib
{
    private $user_id = 0;
    private $cer_id = 0;

    private $ocr2_uri = '';
    private $my_host = '';

    private $aes_cipher = 'aes-128-cbc'; // 加密算法
    private $aes_iv = '0102030405060708'; // 初始向量參數
    private $client;

    private $secret_key = '';
    private $public_key = '';
    private $session_id = '';

    function __construct($params)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/Log_ocr2_model');

        $this->user_id = $params['user_id'];
        $this->cer_id = $params['cer_id'];

        $this->ocr2_uri = getenv('OCR2_API_URI');
        $this->my_host = getenv('OCR2_LOCAL_HOST');

        $this->_init_client();
    }

    private function _init_client()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => $this->ocr2_uri,
            'timeout' => 60,
        ]);

        $this->secret_key = $this->_create_secret_key();
        $this->public_key = $this->_get_public_key();
        $this->session_id = $this->_session_register();
    }

    public function check_ocr_connection()
    {
        $data = [
            'error' => TRUE,
            'msg' => '',
        ];

        if ($this->_is_valid())
        {
            $data['error'] = FALSE;
            $data['msg'] = '成功與 ocr 建立連線! secret_key: ' . $this->secret_key . ', session_id: ' . $this->session_id;
        }
        else
        {
            $data['msg'] = '與 ocr 建立連線失敗!';
            if (empty($this->public_key))
            {
                $data['msg'] .= '沒有取得 ocr public key，請檢查環境變數(OCR2_API_URI)是否正確。';
            }

            if (empty($this->session_id))
            {
                $data['msg'] .= '沒有註冊 ocr session，請檢查環境變數(OCR2_LOCAL_HOST)是否正確，或查詢資料庫錯誤紀錄。';
            }
        }

        return $data;
    }

    public function identity_verification($images)
    {
        if ( ! $this->_is_valid())
        {
            $this->_init_client();
        }

        $router = '/identity_verification';
        try
        {
            $res = $this->client->request('POST', $router, [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Session-ID' => $this->session_id,
                ],
                'query' => [
                    'add_task' => FALSE,
                ],
                'body' => json_encode([
                    'data' => $this->_aes_encode(json_encode($images)),
                ]),
            ]);
        }
        catch (Exception $e)
        {
            $this->_log_event(
                $router,
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getBody()
            );
            return '';
        }

        // 成功的話紀錄回傳的辨識結果
        $this->_log_event(
            $router,
            $res->getStatusCode(),
            $res->getBody()
        );

        return json_decode($res->getBody(), TRUE);
    }

    public function combine_ymd($card)
    {
        $return_data = [
            'birth' => '',
            'apply' => '',
        ];

        if (empty($card['birth_yyy']))
        {
            return $return_data;
        }

        $return_data['birth'] = $card['birth_yyy'] .
        str_pad($card['birth_mm'], 2, '0', STR_PAD_LEFT) .
        str_pad($card['birth_dd'], 2, '0', STR_PAD_LEFT);

        if (isset($card['apply_yyy']))
        {
            $return_data['apply'] = $card['apply_yyy'] .
            str_pad($card['apply_mm'], 2, '0', STR_PAD_LEFT) .
            str_pad($card['apply_dd'], 2, '0', STR_PAD_LEFT);
        }

        return $return_data;
    }

    public function transfer_apply_code($code)
    {
        $list = [
            '0' => '未領',
            '1' => '初發',
            '2' => '補發',
            '3' => '換發',
        ];

        return ($code === '') ? '' : $list[$code];
    }

    public function transfer_issue_site($site)
    {
        $list = [
            '09007' => '連江',
            '09020' => '金門',
            '10001' => '北縣',
            '10002' => '宜縣',
            '10003' => '桃縣',
            '10004' => '竹縣',
            '10005' => '苗縣',
            '10006' => '中縣',
            '10007' => '彰縣',
            '10008' => '投縣',
            '10009' => '雲縣',
            '10010' => '嘉縣',
            '10011' => '南縣',
            '10012' => '高縣',
            '10013' => '屏縣',
            '10014' => '東縣',
            '10015' => '花縣',
            '10016' => '澎縣',
            '10017' => '基市',
            '10018' => '竹市',
            '10020' => '嘉市',
            '63000' => '北市',
            '64000' => '高市',
            '65000' => '新北市',
            '66000' => '中市',
            '67000' => '南市',
            '68000' => '桃市',
        ];

        return ($site === '') ? '' : $list[$site];
    }

    public function get_gender_from_id_number(String $number)
    {
        return preg_match('/^[A-Z]{1}[0-9]{9}/', $number) ?
        $this->_transfer_gender_name($number[1]) : '';
    }

    private function _transfer_gender_name($num)
    {
        $list = [
            '1' => '男',
            '2' => '女',
        ];

        return $list[$num];
    }

    private function _session_register()
    {
        $router = '/session/registry';
        try
        {
            $res = $this->client->request('POST', $router, [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'data' => $this->_get_encrypted_host_dot_secret_key(),
                ]),
            ]);
        }
        catch (Exception $e)
        {
            $this->_log_event(
                $router,
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getBody()
            );
            return '';
        }

        return json_decode($res->getBody(), TRUE)['session_id'];
    }

    private function _get_public_key()
    {
        $router = '/rsa_public_key';
        try
        {
            $res = $this->client->request('GET', $router);
        }
        catch (Exception $e)
        {
            $this->_log_event(
                $router,
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getBody()
            );
            return '';
        }

        return $res->getBody()->getContents();
    }

    private function _create_secret_key()
    {
        $secret_key = '';
        $text = 'abcdeABCDE1234567890';

        for ($i = 0; $i < 16; $i++)
        {
            $secret_key .= $text[random_int(0, 19)];
        }

        return $secret_key;
    }

    private function _log_event($router, $status = 0, $response = '')
    {
        $log_data = [
            'router' => $router,
            'http_status' => $status,
            'user_id' => $this->user_id,
            'cer_id' => $this->cer_id,
            'secret_key' => $this->secret_key,
            'session_id' => $this->session_id,
            'response' => $response,
        ];
        $this->CI->Log_ocr2_model->insert($log_data);
    }

    private function _is_valid()
    {
        return ($this->client instanceof GuzzleHttp\client
            && $this->session_id !== '') ? TRUE : FALSE;
    }

    /**
     *  PHP 提供的 openssl 金鑰加密功能
     * openssl_public_encrypt($str, $crypted, $key)
     * 預設使用 SHA1 演算法加密，且無法更改其設定
     * 但因 OCR 使用的加解密方式為 MGF1(algorithm=hashes.SHA256())
     * 所以只能另外導入 phpseclib 來進行加密動作
     * https://github.com/phpseclib/phpseclib
     **/
    private function _get_encrypted_host_dot_secret_key()
    {
        $str = $this->my_host . '.' . $this->secret_key;
        $key = PublicKeyLoader::load($this->public_key)->withMGFHash('sha256');
        return bin2hex($key->encrypt($str));
    }

    private function _aes_encode($images)
    {
        return openssl_encrypt($images, $this->aes_cipher,
            $this->secret_key, 0, $this->aes_iv);
    }

}