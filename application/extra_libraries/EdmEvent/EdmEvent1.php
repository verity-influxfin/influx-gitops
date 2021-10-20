<?php

namespace EdmEvent;

/**
 * 王道銀行(平台上班族還款中/已結案申貸戶)
 * Class EdmEvent1
 * @package EdmEvent
 */
class EdmEvent1 extends EdmEventBase
{
    protected $event_id = 1;

    public function __construct($event)
	{
        parent::__construct($event);

        $this->CI->load->model('user/edm_event_log_model');
        $this->CI->load->library('Notification_lib');
	}

	public function getReceivers() {
        $this->receivers = $this->CI->edm_event_log_model->getUnsentUsersByObankEvent($this->event_id, USER_BORROWER, self::TYPE_EMAIL);
        $this->receivers = array_filter($this->receivers, function($item) {
            return in_array($item['status'], [TARGET_REPAYMENTING, TARGET_REPAYMENTED]);
        });
    }

    protected function _send(): int
    {
        return $this->sendEmail();
    }
}
