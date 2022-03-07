<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;
use Google\Api\Backend;

/**
 * 社交帳號徵信項
 * Class Social
 * @package Certification
 */
class Cert_social extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_SOCIAL;

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
     * @var array 轉換後的資料
     */
    private $transform_data = [];

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
        if (isset($this->certification['user_id']) && isset($content['facebook']['access_token']) && isset($content['instagram']['username']))
        {
            // fb是否綁定
            if (isset($content['facebook']['email']) && isset($content['facebook']['name']))
            {
                // ig爬蟲狀態
                $ig_username = trim($content['instagram']['username']);
                $this->CI->load->library('scraper/instagram_lib');
                $log_status = $this->CI->instagram_lib->getLogStatus($this->certification['user_id'], $ig_username);
                if ($log_status || isset($log_status['status']))
                {
                    if ($log_status['status'] == SCRAPER_STATUS_SUCCESS && isset($log_status['response']['result']['status']))
                    {
                        // IG爬蟲結束
                        if ($log_status['response']['result']['status'] == 'finished')
                        {
                            $risk_control_info = $this->CI->instagram_lib->getRiskControlInfo($this->certification['user_id'], $ig_username);
                            if ($risk_control_info && isset($risk_control_info['status']) && $risk_control_info['status'] == SCRAPER_STATUS_SUCCESS)
                            {
                                $usernameExist = isset($risk_control_info['response']['result']['isExist']) ? $risk_control_info['response']['result']['isExist'] : '';
                                $isPrivate = isset($risk_control_info['response']['result']['isPrivate']) ? $risk_control_info['response']['result']['isPrivate'] : '';
                                $allPostCount = isset($risk_control_info['response']['result']['posts']) ? $risk_control_info['response']['result']['posts'] : '';
                                $followStatus = isset($risk_control_info['response']['result']['followStatus']) ? $risk_control_info['response']['result']['followStatus'] : '';
                                $isFollower = isset($risk_control_info['response']['result']['isFollower']) ? $risk_control_info['response']['result']['isFollower'] : '';
                                $allFollowingCount = isset($risk_control_info['response']['result']['following']) ? $risk_control_info['response']['result']['following'] : '';
                                $allFollowerCount = isset($risk_control_info['response']['result']['followers']) ? $risk_control_info['response']['result']['followers'] : '';
                                $postsIn3Months = isset($risk_control_info['response']['result']['postsIn3Months']) ? $risk_control_info['response']['result']['postsIn3Months'] : '';
                                $postsWithKeyWords = isset($risk_control_info['response']['result']['postsWithKeyWords']) ? $risk_control_info['response']['result']['postsWithKeyWords'] : '';
                                // 帳號是否存在
                                if ($usernameExist === TRUE)
                                {
                                    $usernameExist = '是';
                                    $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
                                }
                                else if ($usernameExist === FALSE)
                                {
                                    $usernameExist = '否';
                                    $this->result->addMessage('IG未爬到正確資訊(帳號不存在)', CERTIFICATION_STATUS_FAILED, MessageDisplay::Backend);
                                    $this->result->addMessage('IG提供帳號無效請確認', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
                                }
                                else
                                {
                                    $this->result->addMessage('IG爬蟲確認帳號是否存在功能出現錯誤', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                }
                                // 是否為私人帳號判斷
                                if ($isPrivate === TRUE)
                                {
                                    if ($followStatus == 'unfollowed')
                                    {
                                        $this->CI->instagram_lib->autoFollow($this->certification['user_id'], $ig_username);
                                        return FALSE;
                                    }
                                    else if ($followStatus == 'waitingFollowAccept')
                                    {
                                        $follow_status = $this->CI->instagram_lib->getLogStatus($this->certification['user_id'], $ig_username, 'follow');
                                        if ($follow_status && isset($follow_status['status']))
                                        {
                                            if ($follow_status['status'] == SCRAPER_STATUS_SUCCESS && isset($follow_status['response']['result']['status']))
                                            {
                                                if ($follow_status['response']['result']['status'] !== 'finished')
                                                {
                                                    return FALSE;
                                                }
                                            }
                                            else
                                            {
                                                $this->result->addMessage('IG爬蟲結果回應錯誤(子系統回應非200)', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                            }
                                        }
                                        else
                                        {
                                            $this->result->addMessage('IG爬蟲結果無回應(子系統無回應)', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                        }
                                    }
                                }
                                // 是否為活躍社交帳號判斷
                                if (is_numeric($allFollowerCount) && is_numeric($allFollowingCount))
                                {
                                    if ($allFollowerCount <= FOLLOWER_ACTIVATE && $allFollowingCount <= FOLLOWING_ACTIVATE)
                                    {
                                        $this->result->addMessage('IG非活躍帳號', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                    }
                                }
                                $content['instagram'] = [
                                    'username' => $ig_username,
                                    'usernameExist' => $usernameExist,
                                    'info' => [
                                        'isPrivate' => $isPrivate,
                                        'followStatus' => $followStatus,
                                        'isFollower' => $isFollower,
                                        'allPostCount' => $allPostCount,
                                        'allFollowerCount' => $allFollowerCount,
                                        'allFollowingCount' => $allFollowingCount
                                    ]
                                ];
                                $content['meta'] = [
                                    'follow_count' => $allFollowerCount,
                                    'posts_in_3months' => $postsIn3Months,
                                    'key_word' => $postsWithKeyWords
                                ];
                            }
                            else
                            {
                                $this->result->addMessage('IG爬蟲結果無回應(子系統無回應)', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                            }
                        }
                        // IG爬蟲狀態錯誤
                        else if ($log_status['response']['result']['status'] == 'failure')
                        {
                            $this->result->addMessage('IG爬蟲執行失敗', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                        }
                        // 其餘狀態下次跑批再處理
                        else
                        {
                            return FALSE;
                        }
                    }
                    // 沒有IG爬蟲紀錄查詢log紀錄
                    else if ($log_status['status'] == SCRAPER_STATUS_NO_CONTENT)
                    {
                        $this->CI->instagram_lib->updateRiskControlInfo($this->certification['user_id'], $ig_username);
                        return FALSE;
                    }
                    else
                    {
                        $this->result->addMessage('IG爬蟲回應非200或找不到爬蟲回應', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    }
                }
                else
                {
                    $this->result->addMessage('IG爬蟲沒有回應', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                }
            }
            else
            {
                $this->result->addMessage('FB帳號未綁定', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            }
        }
        else
        {
            $this->result->addMessage('社交認證尚有缺少認證項', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
        }

        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);
            return FALSE;
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

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
    }
}