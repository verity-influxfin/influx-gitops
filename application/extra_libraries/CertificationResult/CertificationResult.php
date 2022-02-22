<?php
namespace CertificationResult;
defined('BASEPATH') OR exit('No direct script access allowed');

interface CertificationResultInterface
{
	public function addMessage($msg, $status, $showFlag);
	public function getMessage($status);
	public function getAllMessage($showFlag);
	public function getAPPMessage($status);
	public function getStatus();
	public function setStatus($status);
	public function setBanResubmit($resubmitExpirationMonth);
	public function getCanResubmitDate();
}

abstract class MessageDisplay
{
	const Client = 0;
	const Backend = 1;
	const Debug = 2;
	const All = 3;
}

class CertificationResult implements CertificationResultInterface
{
	protected $msgList;
	protected $manualStatus;
	protected $subStatus;
	protected $resubmitExpirationMonth;
	protected $banResubmit;
    public static $FAILED_MESSAGE = '經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務。';

	public function __construct($status = 0, $resubmitExpirationMonth=6)
	{
		$this->manualStatus = $status;
		$this->subStatus = 0;
		$this->resubmitExpirationMonth = $resubmitExpirationMonth;
		$this->clear();
	}

	public function addMessage($msg, $status, $showFlag=MessageDisplay::All) {
		$this->msgList[$status][] = [$msg, $showFlag];
	}

	public function getMessage($status, $showFlag=MessageDisplay::All): array {
		if(array_key_exists($status, $this->msgList)) {
			$result = array_filter($this->msgList[$status], function ($msg) use ($showFlag) {
				return $msg[1] <= $showFlag;
			});
			if (is_array($result))
				return array_unique(array_column($result, 0));
		}
		return [];
	}

	public function getAllMessage($showFlag=MessageDisplay::All): array
	{
		if(array_key_exists(2, $this->msgList)) {
			return $this->getMessage(2, $showFlag);
		}else {
			return array_reduce(array_keys($this->msgList), function ($msg, $status) use ($showFlag) {
				return array_merge($msg, $this->getMessage($status, $showFlag));
			}, []);
		}
	}

	public function getAPPMessage($status): array
	{
		return $this->getMessage($status, MessageDisplay::Client);
	}

	public function setStatus($status) {
		$this->manualStatus = $status;
	}

	public function getStatus(): int
	{
		return array_key_exists(2, $this->msgList) ? 2
			: (array_key_exists(3, $this->msgList) ? 3 : $this->manualStatus);
	}

	public function setBanResubmit($resubmitExpirationMonth=null) {
		if(isset($resubmitExpirationMonth))
			$this->resubmitExpirationMonth = $resubmitExpirationMonth;
		$this->banResubmit = true;
	}

    public function clear() {
        $this->msgList = [];
        $this->banResubmit = 0;
    }

	public function getCanResubmitDate($timestamp=0): string
	{
		$canResubmitDate = '';

		if ($this->banResubmit) {
			$canResubmitDate = new \DateTime;
			if ($timestamp)
				$canResubmitDate->setTimestamp($timestamp);
			$canResubmitDate->modify( '+'.$this->resubmitExpirationMonth.' month' );
			$canResubmitDate = $canResubmitDate->format('Y-m-d H:i:s');
		}
		return $canResubmitDate;
	}

    public function setSubStatus($status) {
        $this->subStatus = $status;
    }

    public function getSubStatus() {
        return $this->subStatus;
    }

    /**
     * 轉換為 JSON 資料格式
     * @param bool $pretty_print
     * @return false|string
     */
    public function jsonDump(bool $pretty_print=TRUE)
    {
        return json_encode(
                ['msgList' => $this->msgList, 'manualStatus' => $this->manualStatus,
                    'resubmitExpirationMonth' => $this->resubmitExpirationMonth, 'banResubmit' => $this->banResubmit],
                $pretty_print ? JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT : JSON_UNESCAPED_SLASHES
            );
    }

    /**
     * 轉換為 JSON 資料格式
     * @param $cert
     * @return bool
     */
    public function loadResult($cert): bool
    {
        $content = '';
        if(!isset($cert)) {
            return FALSE;
        }else if(is_object($cert) && isset($cert->content)) {
            $content = $cert->content;
        }else if(is_array($cert) && isset($cert['content'])) {
            $content = $cert['content'];
        }

        $data = json_decode($content, TRUE);
        if(isset($data['result']) && is_array($data['result']))
        {
            $result = $data['result'];
            $this->msgList = $result['msgList'] ?? $this->msgList;
            $this->manualStatus = $result['manualStatus'] ?? $this->manualStatus;
            $this->resubmitExpirationMonth = $result['resubmitExpirationMonth'] ?? $this->resubmitExpirationMonth;
            $this->banResubmit = $result['banResubmit'] ?? $this->banResubmit;
            return TRUE;
        }
        return FALSE;
    }
}
