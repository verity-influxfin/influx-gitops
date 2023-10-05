<?php

namespace Certification;

use Certification_ocr\Parser\Ocr_parser_factory;
use CertificationResult\MessageDisplay;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 變更登記事項表/商業登記證明
 * Class Governmentauthorities
 * @package Certification
 */
class Cert_governmentauthorities extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_GOVERNMENTAUTHORITIES;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 6;

    /**
     * @var array 驗證後的額外資料
     */
    private $additional_data = [];

    /**
     * 所有項目是否已提交
     * @override
     * @return bool
     */
    public function is_submitted(): bool
    {
        return TRUE;
    }

    /**
     * 解析輸入資料
     * @return array|mixed
     */
    public function parse()
    {
        return array_merge($this->content, $this->_get_ocr_info());
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool
    {
        return TRUE;
    }

    /**
     * 驗證格式是否正確
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_format($content): bool
    {
        return TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        if ($this->_chk_ocr_status($content) === FALSE)
        {
            return FALSE;
        }

        $data = [];
        $group_id = $content['group_id'] ?? '';
        $imageIds[] = $group_id;
        $count_array = [
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
        ];

        // 找不到資料來源，找 ocr 結果
        if (isset($group_id) && ! isset($content['result'][$group_id]['origin_type']))
        {
            $this->CI->load->library('ocr/report_scan_lib');
            $batchType = 'amendment_of_registers';
            $response = $this->CI->report_scan_lib->requestForResult($batchType, $imageIds);

            if ($response && $response->status == 200)
            {
                $response = $response->response->amendment_of_register_logs->items[0] ?? '';
                if ($response && $response->status == 'finished')
                {
                    // 變卡ocr資料
                    $data[$group_id]['company_owner'] = $response->amendment_of_register->companyInfo->owner ?? '';
                    $data[$group_id]['tax_id'] = $response->amendment_of_register->companyInfo->taxId ?? '';
                    $data[$group_id]['company_address'] = $response->amendment_of_register->companyInfo->address ?? '';
                    $data[$group_id]['company_name'] = $response->amendment_of_register->companyInfo->name ?? '';
                    $data[$group_id]['capital_amount'] = $response->amendment_of_register->companyInfo->amountOfCapital ?? '';
                    $data[$group_id]['paid_in_capital_amount'] = $response->amendment_of_register->companyInfo->paidInCapital ?? '';
                    // to do : 董監事資料待補上?
                }
                else
                {
                    $this->result->addMessage(
                        '找不到變卡ocr資料',
                        CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                        MessageDisplay::Backend
                    );
                }
            }
        }
        // 使用者校正資料
        if (isset($content['result'][$group_id]['origin_type']) && $content['result'][$group_id]['origin_type'] == 'user_confirm')
        {
            $data[$group_id]['company_owner'] = $content['result'][$group_id]['owner'] ?? '';
            $data[$group_id]['owner_id'] = $content['result'][$group_id]['owner_id'] ?? '';
            $data[$group_id]['tax_id'] = $content['result'][$group_id]['tax_id'] ?? '';
            $data[$group_id]['company_address'] = $content['result'][$group_id]['address'] ?? '';
            $data[$group_id]['company_name'] = $content['result'][$group_id]['name'] ?? '';
            $data[$group_id]['capital_amount'] = $content['result'][$group_id]['capital'] ?? '';
            $data[$group_id]['paid_in_capital_amount'] = $content['result'][$group_id]['capital'] ?? '';
            // 董監事
            for ($i = 1; $i <= 7; $i++)
            {
                $data[$group_id]["Director{$count_array[$i]}Id"] = $info->content['result'][$group_id]["Director{$count_array[$i]}Id"] ?? '';
                $data[$group_id]["Director{$count_array[$i]}Name"] = $info->content['result'][$group_id]["Director{$count_array[$i]}Name"] ?? '';
            }
        }
        if ($data)
        {
            // 變卡正確性驗證
            $this->CI->load->library('verify/data_legalize_lib');
            $res = $this->CI->data_legalize_lib->legalize_governmentauthorities($this->certification['user_id'], $data);
            // 寫入結果(不論對錯都寫入，方便查驗)

            if (empty($res['error_message']))
            {
                $this->result->addMessage($res['error_message'], CERTIFICATION_STATUS_SUCCEED);
            }
            else
            {
                $this->result->addMessage($res['error_message'], CERTIFICATION_STATUS_PENDING_TO_REVIEW);
            }
            $this->additional_data['error_location'] = $res['error_location'];
            $this->additional_data['result'][$imageIds[0]] = [
                'action_user' => 'system',
                'send_time' => time(),
                'status' => 1,
                'tax_id' => $data[$group_id]['tax_id'] ?? '',
                'name' => $data[$group_id]['company_name'] ?? '',
                'capital' => $data[$group_id]['capital_amount'] ?? '',
                'address' => $data[$group_id]['company_address'] ?? '',
                'owner' => $data[$group_id]['company_owner'] ?? '',
                'owner_id' => $data[$group_id]['owner_id'] ?? '',
                'company_type' => $res['result']['company_type'] ?? '',
            ];
            for ($i = 1; $i <= 7; $i++)
            {
                $this->additional_data['result'][$imageIds[0]]["Director{$count_array[$i]}Id"] = $data["Director{$count_array[$i]}Id"] ?? '';
                $this->additional_data['result'][$imageIds[0]]["Director{$count_array[$i]}Name"] = $data["Director{$count_array[$i]}Name"] ?? '';
            }
        }
        // 爬蟲資料結果
        $user_info = $this->CI->user_model->get_by(array('id' => $this->certification['user_id']));
        if ($user_info && ! empty($this->content['compId']))
        {
            // 確認爬蟲狀態
            $this->CI->load->library('scraper/Findbiz_lib');
            $resp = $this->CI->findbiz_lib->getFindBizStatus($this->content['compId']);
            if ( ! isset($resp['response']['result']['status']) || ($resp['response']['result']['status'] != 'failure' && $resp['response']['result']['status'] != 'finished'))
            {
                // 爬蟲沒打過重打一次
                if ($resp && isset($resp['status']) && $resp['status'] == 204)
                {
                    $this->CI->findbiz_lib->requestFindBizData($this->content['compId']);
                }
                $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
                return FALSE;
            }
            // 商業司截圖(for新光普匯微企e秒貸)
            $company_image_url = $this->CI->findbiz_lib->getFindBizImage($this->content['compId'], $user_info->id);
            if ($company_image_url)
            {
                $this->additional_data['governmentauthorities_image'][] = $company_image_url;
            }
            // 商業司歷任負責人
            $company_scraper_info = $this->CI->findbiz_lib->getResultByBusinessId($this->content['compId']);
            if ($company_scraper_info && $company_scraper_info['status'] == 200)
            {
                if ( ! isset($this->additional_data['scraper']))
                {
                    $this->additional_data['scraper'] = [];
                }
                $this->additional_data['scraper']['DepartmentOfCommerce'] = json_encode($company_scraper_info['response']['result']['firstPage']);
                $company_user_info = $this->CI->findbiz_lib->searchEachTermOwner($company_scraper_info);
                if ($company_user_info)
                {
                    krsort($company_user_info);
                    $num = 0;
                    foreach ($company_user_info as $k => $v)
                    {
                        if ($num == 0)
                        {
                            $this->additional_data['PrOnboardDay'] = $k;
                            $this->additional_data['PrOnboardName'] = $v;
                        }
                        if ($num == 1)
                        {
                            $this->additional_data['ExPrOnboardDay'] = $k;
                            $this->additional_data['ExPrOnboardName'] = $v;
                        }
                        if ($num == 2)
                        {
                            $this->additional_data['ExPrOnboardDay2'] = $k;
                            $this->additional_data['ExPrOnboardName2'] = $v;
                        }
                        if ($num == 3)
                        {
                            break;
                        }
                        $num++;
                    }
                }
            }

            $this->CI->load->library('scraper/business_registration_lib');
            $business_register = $this->CI->business_registration_lib->getResultByBusinessId($this->content['compId']);
            if (isset($business_register) && $business_register['status'] == 200)
            {
                $this->additional_data['scraper']['MinistryOfFinance'] = $business_register['response'];
            }
        }
        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        $expire_time = new \DateTime;
        $expire_time->setTimestamp($this->certification['created_at']);
        $expire_time->modify("+ {$this->valid_month} month");
        $this->expired_timestamp = $expire_time->getTimestamp();

        return TRUE;
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        $this->CI->load->helper('user_certification');
        $this->CI->load->library('scraper/judicial_yuan_lib');
        $domicile = get_domicile($this->content['compRegAddress'] ?? '');
        if ($domicile != '')
        {
            $this->CI->judicial_yuan_lib->request_verdicts($this->content['compName'], $domicile);
        }
        else
        {
            log_message('error', "[post_success]變更登記卡的爬蟲因找不到縣市而忽略觸發。地址：{$domicile}");
        }

        $this->CI->load->library('mapping/user/certification_data');
        $update_user_param = [];
        if ( ! empty($this->content['compName']))
        {
            $update_user_param['name'] = $this->content['compName'];
        }
        if ( ! empty($this->content['address']))
        {
            $update_user_param['address'] = $this->content['address'];
        }
        if ( ! empty($update_user_param))
        {
            $this->CI->user_model->update($this->certification['user_id'], $update_user_param);
        }

        $company = $this->CI->user_model->get_by(['id' => $this->certification['user_id']]);
        $user = $this->CI->user_model->get_by(['phone' => $company->phone, 'company_status' => 0]);
        if ( ! empty($user))
        {
            // 新建法人歸戶資料
            $param = [
                'user_id' => $user->id,
                'company_type' => $this->content['compType'] ?? '',
                'company' => $this->content['compName'] ?? ($company->name ?? ''),
                'company_user_id' => $this->certification['user_id'],
                'tax_id' => $this->content['compId'] ?? ($company->id_number ?? ''),
                'status' => 3,
                'enterprise_registration' => json_encode(['enterprise_registration_image' => $this->content['governmentauthorities_image']])
            ];
            $this->CI->load->model('user/judicial_person_model');
            $judicial_person_info = $this->CI->judicial_person_model->get_by(['company_user_id' => $this->certification['user_id']]);
            if ($judicial_person_info)
            {
                $rs = $this->CI->judicial_person_model->update_by(['company_user_id' => $this->certification['user_id']], $param);
            }
            else
            {
                $rs = $this->CI->judicial_person_model->insert($param);
            }

            if ( ! $rs)
            {
                return FALSE;
            }
        }

        return $this->CI->certification_lib->fail_other_cer($this->certification);
    }

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool
    {

        return TRUE;
    }

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool
    {
        return TRUE;
    }

    // 要跑的 OCR 辨識
    private function _get_ocr_info(): array
    {
        $result = [];
        if ( ! isset($this->content['ocr_parser']['res']))
        {
            $cert_ocr_parser = Ocr_parser_factory::get_instance($this->certification);
            $ocr_parser_result = $cert_ocr_parser->get_result();
            if ($ocr_parser_result['success'] === TRUE)
            {
                if ($ocr_parser_result['code'] == 201 || $ocr_parser_result['code'] == 202)
                { // OCR 任務剛建立，或是 OCR 任務尚未辨識完成
                    return $result;
                }
                $result['ocr_parser']['res'] = TRUE;
                $result['ocr_parser']['content'] = $ocr_parser_result['data'];
            }
            else
            {
                $result['ocr_parser']['res'] = FALSE;
                $result['ocr_parser']['msg'] = $ocr_parser_result['msg'];
            }
        }

        return $result;
    }

    /**
     * OCR 辨識後的檢查
     * @param $content
     * @return bool
     */
    private function _chk_ocr_status($content): bool
    {
        if ( ! isset($content['ocr_parser']['res']))
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            return FALSE;
        }
        else
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);
        }
        return TRUE;
    }

    public function post_verify(): bool
    {
        if (empty($this->additional_data))
        {
            return TRUE;
        }

        $certification_info = $this->CI->user_certification_model->get($this->certification['id']);
        $content = json_decode($certification_info->content ?? [], TRUE);
        $result = $this->CI->user_certification_model->update($this->certification['id'], [
            'content' => json_encode(array_replace_recursive($content, $this->additional_data), JSON_INVALID_UTF8_IGNORE)
        ]);

        if ($result)
        {
            return TRUE;
        }

        return FALSE;
    }
}