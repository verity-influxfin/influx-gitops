<?php

namespace EdmEvent;

/**
 * 王道導流(平台上班族未核准之申貸戶)
 * Class EdmEvent2
 * @package EdmEvent
 */
class EdmEvent2 extends EdmEventBase
{
    protected $event_id = 2;

    public function __construct($event)
	{
        parent::__construct($event);

        $this->CI->load->model('user/edm_event_log_model');
        $this->CI->load->library('Notification_lib');
	}

	public function getReceivers() {
        $this->receivers = $this->CI->edm_event_log_model->getUnsentUsersByObankEvent($this->event_id, USER_BORROWER, self::TYPE_EMAIL);
        $this->receivers = array_filter($this->receivers, function($item) {
            return $item['status'] == TARGET_FAIL;
        });
    }

    protected function _send(): int
    {
        return $this->sendEmail();
    }
}
