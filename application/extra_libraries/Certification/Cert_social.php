<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;
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
        if (! isset($this->certification['user_id']))
        {
            $this->result->addMessage('此認證項缺少user_id', CERTIFICATION_STATUS_FAILED, MessageDisplay::Debug);
            return FALSE;
        }
        if (! isset($content['facebook']['access_token']))
        {
            $this->result->addMessage('FB帳號未綁定', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        if (! isset($content['instagram']['username']))
        {
            $this->result->addMessage('未提供Instagram帳號', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        if(! isset($content['facebook']['email']))
        {
            $this->result->addMessage('FB帳號缺少email', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        if (! isset($content['facebook']['name']))
        {
            $this->result->addMessage('FB帳號缺少姓名', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        $ig_username = $content['instagram']['username'];
        if (! $ig_username)
        {
            $this->result->addMessage('IG帳號不可為空', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        if (mb_strlen($ig_username, mb_detect_encoding($ig_username)) != strlen($ig_username))
        {
            $this->result->addMessage('IG帳號不可包含中文', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        // 預設status給0
        $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
        $ig_username = trim($content['instagram']['username']);
        $this->CI->load->library('scraper/instagram_lib');
        // 確認IG爬蟲狀態
        $log_st = $this->check_log($ig_username, 'riskControlInfo');
        if ($log_st)
        {
            // 取得資料
            $risk_control_info = $this->CI->instagram_lib->getRiskControlInfo($this->certification['user_id'], $ig_username);
            if ($risk_control_info && isset($risk_control_info['status']) && $risk_control_info['status'] == SCRAPER_STATUS_SUCCESS)
            {
                $risk_res            = $risk_control_info['response']['result'];
                $username_exist      = $risk_res['isExist'] ? '是' : '否';
                $is_private          = $risk_res['isPrivate'] ?? '';
                $follow_status       = $risk_res['followStatus'] ?? '';
                $all_follower_count  = $risk_res['followers'] ?? '';
                $all_following_count = $risk_res['following'] ?? '';
                $this->additional_data['instagram'] = [
                    'username'              => $ig_username,
                    'usernameExist'         => $username_exist,
                    'info' => [
                        'isPrivate'         => $is_private,
                        'followStatus'      => $follow_status,
                        'isFollower'        => $risk_res['isFollower'] ?? '',
                        'allPostCount'      => $risk_res['posts'] ?? '',
                        'allFollowerCount'  => $all_follower_count,
                        'allFollowingCount' => $all_following_count
                    ]
                ];
                $this->additional_data['meta'] = [
                    'follow_count'          => $all_follower_count,
                    'posts_in_3months'      => $risk_res['postsIn3Months'] ?? '',
                    'posts_in_1months'       => (int) $risk_res['postsIn1Months'] ?? '', // 一個月內發文數
                    'key_word'              => $risk_res['postsWithKeyWords'] ?? '',
                    'followers_grow_rate_in_3month' => (double) $risk_res['followersGrowRateIn3Month'] ?? '', // 三個月內增幅
                ];

                // 帳號是否存在
                if ($username_exist == '是')
                {
                    // 是否為私人帳號判斷
                    if ($is_private === TRUE)
                    {
                        $log_st = $this->check_log($ig_username, 'follow');
                        if ( ! $log_st)
                        {
                            // 在此中斷func執行確保驗證狀態停在待驗證
                            return FALSE;
                        }
                        // 假如狀態為未追蹤重打Follow
                        if ($follow_status == 'unfollowed')
                        {
                            $this->CI->instagram_lib->autoFollow($this->certification['user_id'], $ig_username);
                            // 在此中斷func執行確保驗證狀態停在待驗證
                            return FALSE;
                        }
                    }
                    // 驗證成功
                    $this->result->setStatus(CERTIFICATION_STATUS_SUCCEED);
                }
                else
                {
                    $this->result->addMessage('Instagram 帳號不存在', CERTIFICATION_STATUS_FAILED, MessageDisplay::Backend);
                    $this->result->addMessage('Instagram 帳號無效，請確認', CERTIFICATION_STATUS_FAILED, MessageDisplay::Client);
                }

                // 是否為活躍社交帳號判斷
                if ($all_follower_count && $all_following_count && is_numeric($all_follower_count) && is_numeric($all_following_count))
                {
                    if ($all_follower_count <= FOLLOWER_ACTIVATE && $all_following_count <= FOLLOWING_ACTIVATE)
                    {
                        $this->result->addMessage('IG非活躍帳號，請人工確認', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    }
                }
            }
            else
            {
                $this->result->addMessage('IG爬蟲getRiskControlInfo回應異常，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }
        }

        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);
            return FALSE;
        }
        else if ($this->result->getStatus() == CERTIFICATION_STATUS_PENDING_TO_VALIDATE)
        {
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
        $user_meta = array_replace_recursive($this->content['meta'] ?? [], $this->additional_data['meta'] ?? []);
        if ( ! empty($user_meta))
        {
            $this->CI->certification_lib->user_meta_progress($user_meta, $this->certification);
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

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
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

    private function check_log($ig_username, $action): bool
    {
        $log = $this->CI->instagram_lib->getLogStatus($this->certification['user_id'], $ig_username, $action);
        // 確認子系統是否有回應
        if ($log || isset($log['status']))
        {
            switch ($log['status'])
            {
                case SCRAPER_STATUS_SUCCESS:
                    if (isset($log['response']['result']['status']))
                    {
                        switch ($log['response']['result']['status'])
                        {
                            // IG爬蟲結束
                            case 'finished':
                                return TRUE;
                            // IG爬蟲狀態錯誤
                            case 'failure':
                                $this->result->addMessage('IG爬蟲' . $action . '執行失敗，請人工處理', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                                break;
                        }
                    }
                    else
                    {
                        $this->result->addMessage('IG爬蟲' . $action . '資料結構異常，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    }
                    break;
                // log沒有紀錄
                case SCRAPER_STATUS_NO_CONTENT:
                    switch ($action)
                    {
                        case 'riskControlInfo':
                            $this->CI->instagram_lib->updateRiskControlInfo($this->certification['user_id'], $ig_username);
                            break;
                        case 'follow':
                            $this->CI->instagram_lib->autoFollow($this->certification['user_id'], $ig_username);
                            break;
                    }
                    break;
                default:
                    $this->result->addMessage('IG爬蟲' . $action . '回應' . $log['status'] . '，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                    break;
            }
        }
        else
        {
            $this->result->addMessage('IG爬蟲' . $action . '沒有回應，請洽工程師', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }
        return FALSE;
    }
}