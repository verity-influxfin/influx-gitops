<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

use GuzzleHttp\Client;

class Ocr extends REST_Controller
{
    private $user_info;
    private $type_whitelist = [
        'identification_card_front',
        'identification_card_back',
        'national_health_insurance',
    ];

    public function __construct()
    {
        parent::__construct();

        $token = $this->input->request_headers()['request_token'] ?? '';
        $token_data = AUTHORIZATION::getUserInfoByToken($token);
        if (empty($token_data->id) ||
            empty($token_data->phone) ||
            $token_data->expiry_time < time())
        {
            $this->response(['result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT]);
        }

        $this->user_info = $this->user_model->get($token_data->id);
        if ($token_data->auth_otp != $this->user_info->auth_otp)
        {
            $this->response(['result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT]);
        }

        if ($this->user_info->block_status != 0)
        {
            $this->response(['result' => 'ERROR', 'error' => BLOCK_USER]);
        }

        if ($this->request->method != 'get')
        {
            $this->load->model('log/log_request_model');
            $this->log_request_model->insert([
                'method' => $this->request->method,
                'url' => $this->uri->uri_string(),
                'investor' => $token_data->investor,
                'user_id' => $token_data->id,
                'agent' => $token_data->agent,
            ]);
        }
    }

    /**
     * @api {get} /v2/ocr/card_identity 身份證|健保卡資訊辨識
     * @version 0.2.0
     * @group Ocr
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @return Array
     * @example return success
     *     {
     *         "result": "SUCCESS",
     *         "data": {
     *             "national_health_insurance_logs" : {
     *                  "count" : 1,
     *                  "items": [
     *                      {
     *                          "id": 4,
     *                          "status": "finished",
     *                          "result":
     *                          {
     *                              "title": "全民健康保險",
     *                              "name": "姓氏名",
     *                              "birthday": "100/01/01",
     *                              "number": "A123456789",
     *                              "cardNumber": "000012348888"
     *                          },
     *                          "created_at": 1520421572,
     *                          "updated_at": 1520421572
     *                      }
     *                  ]
     *             }
     *         }
     *     }
     *
     * @apiUse INPUT_NOT_CORRECT
     * @apiUse PERMISSION_DENY
     * @apiUse EXIT_ERROR (Ocr subsystem not return anything back)
     */
    public function card_identity_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $type = $input['type'] ?? '';
        $reference_id = $input['reference_id'] ?? '';

        if (empty($reference_id) ||
            ! is_numeric($reference_id) ||
            ! in_array($type, $this->type_whitelist))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $this->load->model('log/log_image_model');
        $image_info = $this->log_image_model->get($reference_id);

        if (empty($image_info))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        if ($image_info->user_id != $this->user_info->id)
        {
            $this->response(['result' => 'ERROR', 'error' => PERMISSION_DENY]);
        }

        $images = [];
        $client = new GuzzleHttp\Client();
        try
        {
            $res = $client->request('GET', $image_info->url);
            $body = $res->getBody()->getContents();
            $images[] = base64_encode($body);

        }
        catch (Exception $e)
        {
            $this->response(['result' => 'ERROR', 'error' => EXIT_ERROR, 'msg' => ' Image not found.']);
        }

        $send_data['img_base64_list'] = $images;

        $this->load->library('Ocr2_lib', [
            'user_id' => $image_info->user_id,
            'cer_id' => 0,
        ]);

        $ocr_result = $this->ocr2_lib->cards_identity($type, $send_data);
        if (empty($ocr_result))
        {
            $this->response(['result' => 'ERROR', 'error' => EXIT_ERROR, 'msg' => ' Result not found.']);
        }

        // 整理回傳結構
        switch ($type)
        {
        case $this->type_whitelist[0]:
            $card_key = 'identification-card';
            $card_info = [
                'title' => '中華民國國民身分證正面',
                'name' => $ocr_result['name'],
                'birthday' => $ocr_result['birth_yyy'] . '年' .
                $ocr_result['birth_mm'] . '月' .
                $ocr_result['birth_dd'] . '日',
                'issueDate' => $ocr_result['apply_yyy'] . '年' .
                $ocr_result['apply_mm'] . '月' .
                $ocr_result['apply_dd'] . '日',
                'issueCity' => $this->ocr2_lib->transfer_issue_site($ocr_result['issue_site_id']),
                'issueType' => $this->ocr2_lib->transfer_apply_code($ocr_result['apply_code_int']),
                'number' => $ocr_result['person_id'],
                'gender' => $this->ocr2_lib->get_gender_from_id_number($ocr_result['person_id']),
            ];
            break;
        case $this->type_whitelist[1]:
            $card_key = 'identification-card';
            $card_info = [
                'title' => '中華民國國民身分證反面',
                'father' => $ocr_result['father_name'],
                'mother' => $ocr_result['mother_name'],
                'spouse' => $ocr_result['spouse_name'],
                'military' => $ocr_result['military'],
                'birthAddress' => $ocr_result['birth_address'],
                'domicile' => $ocr_result['residence_address'],
                'barcode' => $ocr_result['serial_code'],
            ];
            break;
        case $this->type_whitelist[2]:
            $card_key = 'result';
            $card_info = [
                'title' => '全民健康保險',
                'name' => $ocr_result['name'],
                'birthday' => $ocr_result['birth_yyy']
                . '/' . str_pad($ocr_result['birth_mm'], 2, '0', STR_PAD_LEFT)
                . '/' . str_pad($ocr_result['birth_dd'], 2, '0', STR_PAD_LEFT),
                'number' => $ocr_result['person_id'],
                'cardNumber' => $ocr_result['code_str'],
            ];
            break;
        default:
            $card_key = 'undefined';
            $card_info = [];
            break;
        }

        $return_data = [
            $type . '_logs' => [
                'count' => 1,
                'items' => [
                    [
                        'id' => $reference_id,
                        'status' => 'finished',
                        $card_key => $card_info,
                        'created_at' => time(),
                        'updated_at' => time(),
                    ],
                ],
            ],
        ];

        $this->response(['result' => 'SUCCESS', 'data' => $return_data]);
    }
}
