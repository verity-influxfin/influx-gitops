<?php

namespace Certification;

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
        $data = [];
        $group_id = $this->content['group_id'] ?? '';
        $image_ids[] = $group_id;
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
        if (isset($group_id) && ! isset($this->content['result'][$group_id]['origin_type']))
        {
            $this->CI->load->library('ocr/report_scan_lib');
            $batch_type = 'amendment_of_registers';
            $response = $this->CI->report_scan_lib->requestForResult($batch_type, $image_ids);

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
                    $this->result->addMessage('找不到變卡ocr資料', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                }
            }
        }

        // 使用者校正資料
        if (isset($this->content['result'][$group_id]['origin_type']) && $this->content['result'][$group_id]['origin_type'] == 'user_confirm')
        {
            $data[$group_id]['company_owner'] = $this->content['result'][$group_id]['owner'] ?? '';
            $data[$group_id]['owner_id'] = $this->content['result'][$group_id]['owner_id'] ?? '';
            $data[$group_id]['tax_id'] = $this->content['result'][$group_id]['tax_id'] ?? '';
            $data[$group_id]['company_address'] = $this->content['result'][$group_id]['address'] ?? '';
            $data[$group_id]['company_name'] = $this->content['result'][$group_id]['name'] ?? '';
            $data[$group_id]['capital_amount'] = $this->content['result'][$group_id]['capital'] ?? '';
            $data[$group_id]['paid_in_capital_amount'] = $this->content['result'][$group_id]['capital'] ?? '';
            // 董監事
            for ($i = 1; $i <= 7; $i++)
            { // A~G
                $data[$group_id]["Director{$count_array[$i]}Id"] = $this->content['result'][$group_id]["Director{$count_array[$i]}Id"] ?? '';
                $data[$group_id]["Director{$count_array[$i]}Name"] = $this->content['result'][$group_id]["Director{$count_array[$i]}Name"] ?? '';
            }
        }
        if ( ! empty($data))
        {
            // 變卡正確性驗證
            $this->CI->load->library('verify/data_legalize_lib');
            $res = $this->CI->data_legalize_lib->legalize_governmentauthorities($this->certification['user_id'], $data);
            // 寫入結果(不論對錯都寫入，方便查驗)
            if ( ! empty($res['error_message']))
            {
                foreach ($res['error_message'] as $msg)
                {
                    $this->result->addMessage($msg, CERTIFICATION_STATUS_PENDING_TO_REVIEW);
                }
            }
            else
            {
                $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
            }
            $this->content['error_location'] = $res['error_location'];
            $this->content['result'][$image_ids[0]] = [
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
                $this->content['result'][$image_ids[0]]["Director{$count_array[$i]}Id"] = $data["Director{$count_array[$i]}Id"] ?? '';
                $this->content['result'][$image_ids[0]]["Director{$count_array[$i]}Name"] = $data["Director{$count_array[$i]}Name"] ?? '';
            }
        }

        // 爬蟲資料結果
        $user_info = $this->CI->user_model->get_by(array('id' => $this->certification['user_id']));
        if ($user_info && ! empty($user_info->id_number))
        {
            $this->CI->load->library('scraper/Findbiz_lib');
            // 確認爬蟲狀態
            $scraper_status = $this->CI->findbiz_lib->getFindBizStatus($user_info->id_number);
            if ( ! isset($scraper_status->response->result->status) || ! in_array($scraper_status->response->result->status, ['failure', 'finished']))
            {
                // 爬蟲沒打過重打一次
                if ($scraper_status && isset($scraper_status->status) && $scraper_status->status == 204)
                {
                    $this->CI->findbiz_lib->requestFindBizData($user_info->id_number);
                }

                // remark 要清空
                $this->result->clear();
                $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            }
            // 商業司截圖(for新光普匯微企e秒貸)
            $company_image_url = $this->CI->findbiz_lib->getFindBizImage($user_info->id_number, $user_info->id);
            if ($company_image_url)
            {
                $this->content['governmentauthorities_image'][] = $company_image_url;
            }
            // 商業司歷任負責人
            $company_scraper_info = $this->CI->findbiz_lib->getResultByBusinessId($user_info->id_number);
            if ($company_scraper_info)
            {
                $company_user_info = $this->CI->findbiz_lib->searchEachTermOwner($company_scraper_info);
                if ($company_user_info)
                {
                    krsort($company_user_info);
                    $num = 0;
                    foreach ($company_user_info as $k => $v)
                    {
                        switch ($num)
                        {
                            case 0:
                                $this->content['skbank_form']['PrOnboardDay'] = $k;
                                $this->content['skbank_form']['PrOnboardName'] = $v;
                                break;
                            case 1:
                                $this->content['skbank_form']['ExPrOnboardDay'] = $k;
                                $this->content['skbank_form']['ExPrOnboardName'] = $v;
                                break;
                            case 2:
                                $this->content['skbank_form']['ExPrOnboardDay2'] = $k;
                                $this->content['skbank_form']['ExPrOnboardName2'] = $v;
                                break;
                            default:
                                break;
                        }
                        $num++;
                    }
                }
            }
        }

        return $this->content;
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
        $content = $this->content;
        $this->CI->load->library('mapping/user/certification_data');

        // todo: 暫時不寫入 meta
        // $result = ! empty($content['result']) ? $content['result'] : [];
        // $this->CI->certification_data->transformGovernmentauthoritiesToMeta($result);

        // 寫入法人基本資料
        $this->CI->load->model('user/user_model');
        $this->CI->user_model->update($this->certification['user_id'], array(
            'name' => $content['skbank_form']['CompName'] ?? '',
            // 地址暫時不寫入
            'address' => $content['address'] ?? '',
        ));
        // 找自然人資料
        $this->CI->load->model('user/user_model');
        $company = $this->CI->user_model->get_by(['id' => $this->certification['user_id']]);
        $user = $this->CI->user_model->get_by(['phone' => $company->phone, 'company_status' => USER_NOT_COMPANY]);
        if ( ! empty($user))
        {
            // 新建法人歸戶資料
            $param = [
                'user_id' => $user->id,
                'company_type' => $content['skbank_form']['CompType'] ?? '',
                'company' => $content['skbank_form']['CompName'] ?? '',
                'company_user_id' => $this->certification['user_id'],
                'tax_id' => $content['skbank_form']['CompId'] ?? '',
                'status' => 3,
                'enterprise_registration' => json_encode(['enterprise_registration_image' => $this->content['governmentauthorities_image']])
            ];
            $this->CI->load->model('user/judicial_person_model');
            $judical_person_info = $this->CI->judicial_person_model->get_by(['company_user_id' => $this->certification['user_id']]);
            if ($judical_person_info)
            {
                $this->CI->judicial_person_model->update_by(['company_user_id' => $this->certification['user_id']], $param);
            }
            else
            {
                $this->CI->judicial_person_model->insert($param);
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
}