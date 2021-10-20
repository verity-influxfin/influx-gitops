<?php

namespace EdmEvent;

abstract class EdmEventBase implements EdmEventDefinition
{
    protected $event;
    protected $CI;
    protected $event_id = 1;
    protected $receivers;
    protected $user_ids = [];
    public $sentCountList = [];

    /**
     * @throws \Exception
     */
    public function __construct($event)
	{
        if(empty($event)) {
            throw new \Exception("Cannot pass empty parameters");
        }
	    $this->event = $event;
        $this->CI = &get_instance();
	}

	abstract public function getReceivers();
	abstract protected function _send();

    public function send() {
        return $this->_send();
    }

    /**
     * 寄送 Email
     * @return int
     */
    public function sendEmail(): int
    {
        $this->getReceivers();
        $mailList = $this->getSendEmailList(USER_BORROWER);
        $sentCount = 0;
        if(!empty($mailList)) {
            $data = json_decode($this->event['attachment_data'], true);
            $insertLog = [];
            foreach ($this->user_ids as $user_id) {
                $insertLog[] = [
                    'edm_event_id' => $this->event['id'],
                    'user_id' => $user_id,
                    'investor' => USER_BORROWER,
                    'type' => self::TYPE_EMAIL
                ];
            }
            $this->CI->edm_event_log_model->insert_many($insertLog);
            $sentCount = $this->CI->notification_lib->EDM(-1, $this->event['title'], $this->event['content'], $data['EDM_image'] ?? '',
                $data['EDM_href'] ?? '', USER_BORROWER, FALSE, FALSE, FALSE, FALSE, FALSE, $mailList, TRUE);
        }

        $this->sentCountList[self::TYPE_EMAIL] = $sentCount;
        return $sentCount;
    }

    /**
     * 取得欲寄送 Email 列表
     * @param $investor
     * @return array
     */
    public function getSendEmailList($investor): array
    {
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->model("user/user_model");

        $emailList = [];
        $user_ids = array_column($this->receivers, 'user_id');
        if(!empty($user_ids)) {
            // 去除被鎖定的用戶
            $users = $this->CI->user_model->get_many_by([
                'id' => $user_ids,
                'block_status' => 0
            ]);
            $this->user_ids = array_column($users, 'id');
        }

        if (!empty($this->user_ids)) {
            $certs = $this->CI->user_certification_model->get_many_by([
                'certification_id' => CERTIFICATION_EMAIL, 'status' => 1, 'investor' => $investor, 'user_id' => $this->user_ids
            ]);
            foreach ($certs as $cert) {
                $content = json_decode($cert->content, true);
                if (isset($content['email']))
                    $emailList[] = $content['email'];
            }
        }
        return $emailList;
    }

}
