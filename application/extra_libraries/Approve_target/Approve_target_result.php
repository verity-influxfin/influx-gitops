<?php

namespace Approve_target;

class Approve_target_result
{
    public $status; // targets.status
    public $sub_status; // targets.sub_status
    public $remark; // targets.remark
    public $memo; // targets.memo
    public $action; // approve=核可流程 cancel=取消流程

    public const ACTION_APPROVE = 'approve';
    public const ACTION_CANCEL = 'cancel';
    public const TARGET_FAIL_DEFAULT_MSG = '經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務';

    public const DISPLAY_CLIENT = 0;
    public const DISPLAY_BACKEND = 1;
    public const DISPLAY_DEBUG = 2;
    public const DISPLAY_ALL = 3;

    public const DISPLAY_LIST = [
        self::DISPLAY_CLIENT,
        self::DISPLAY_BACKEND,
        self::DISPLAY_DEBUG,
        self::DISPLAY_ALL,
    ];

    public function __construct($status, $sub_status)
    {
        $this->action = self::ACTION_APPROVE;
        $this->status = $status;
        $this->sub_status = $sub_status;
        $this->msg = [];
    }

    /**
     * 設定該次該案的跑批流程繼續
     * @return void
     */
    public function set_action_approve()
    {
        $this->action = self::ACTION_APPROVE;
    }

    /**
     * 設定該次該案的跑批流程取消
     * @return void
     */
    public function set_action_cancel()
    {
        $this->action = self::ACTION_CANCEL;
    }

    /**
     * 該次該案的跑批流程可繼續
     * @return bool
     */
    public function action_is_approve(): bool
    {
        return $this->action === self::ACTION_APPROVE;
    }

    /**
     * 該次該案的跑批流程不可繼續
     * 意指：待使用者交完該交的、爬蟲爬完該爬的，且符合公司風控規定才能繼續下去
     * @return bool
     */
    public function action_is_cancel(): bool
    {
        return $this->action === self::ACTION_CANCEL;
    }

    /**
     * 取得該次該案的跑批流程「繼續」或「取消」
     * @return string
     */
    public function get_action(): string
    {
        return $this->action;
    }

    /**
     * 設定該次該案跑批後的狀態/子狀態
     * @param $status
     * @param $sub_status
     * @return void
     */
    public function set_status($status, $sub_status = NULL)
    {
        $this->status = (int) $status;
        if ($sub_status)
        {
            $this->set_sub_status($sub_status);
        }
    }

    /**
     * 取得該次該案跑批後的狀態
     * @return int
     */
    public function get_status(): int
    {
        return (int) $this->status;
    }

    /**
     * 設定該次該案跑批後的子狀態
     * @param $sub_status
     * @return void
     */
    public function set_sub_status($sub_status)
    {
        $this->sub_status = (int) $sub_status;
    }

    /**
     * 取得該次該案跑批後的子狀態
     * @return int
     */
    public function get_sub_status(): int
    {
        return (int) $this->sub_status;
    }

    /**
     * @param $status : 跑批後的狀態
     * @param $msg : 狀態對應的訊息
     * @return void
     */
    public function add_msg($status, $msg)
    {
        // todo: 目前新訊息會覆蓋舊訊息
        $this->status = $status;
        $this->remark[$status] = $msg;
    }

    /**
     * @param $status
     * @param $msg
     * @param $display
     * @return void
     */
    public function add_memo($status, $msg, $display)
    {
        $this->memo[$status][$display][] = $msg;
    }

    /**
     * @param $status : 跑批後的狀態
     * @return mixed|string
     */
    public function get_msg($status)
    {
        return $this->remark[$status] ?? '';
    }

    /**
     * @param $status
     * @param int $display
     * @return array
     */
    public function get_memo($status, int $display = self::DISPLAY_ALL): array
    {
        return [
            'msg' => [
                $display => ($this->memo[$status][$display] ?? [])
            ]
        ];
    }

    /**
     * @param $status
     * @return array
     */
    public function get_all_memo($status): array
    {
        $memo = $this->memo[$status] ?? [];
        $result = [];
        foreach (self::DISPLAY_LIST as $display)
        {
            $result['msg'][$display] = $memo[$display] ?? [];
        }
        return $result;
    }
}