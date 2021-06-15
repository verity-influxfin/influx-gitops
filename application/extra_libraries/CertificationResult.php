<?php


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

abstract class MassageDisplay
{
	const Client = 0;
	const Backend = 1;
	const Debug = 2;
	const All = 3;
}

class CertificationResult implements CertificationResultInterface
{
	protected $msgList;
	protected $status;
	protected $resubmitExpirationMonth;
	protected $banResubmit;

	public function __construct($status = 0, $resubmitExpirationMonth=6)
	{
		$this->msgList = [];
		$this->status = $status;
		$this->resubmitExpirationMonth = $resubmitExpirationMonth;
		$this->banResubmit = 0;
	}

	public function addMessage($msg, $status, $showFlag=MassageDisplay::All) {
		$this->msgList[$status][] = [$msg, $showFlag];
	}

	public function getMessage($status, $showFlag=MassageDisplay::All): array {
		if(array_key_exists($status, $this->msgList)) {
			$result = array_filter($this->msgList[$status], function ($msg) use ($showFlag) {
				return $msg[1] <= $showFlag;
			});
			if (is_array($result))
				return array_unique(array_column($result, 0));
		}
		return [];
	}

	public function getAllMessage($showFlag=MassageDisplay::All): array
	{
		return array_reduce(range(1,3), function ($msg, $status) use ($showFlag) {
			return array_merge($msg, $this->getMessage($status, $showFlag));
		}, []);
	}

	public function getAPPMessage($status): array
	{
		return $this->getMessage($status, MassageDisplay::Client);
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getStatus(): int
	{
		return array_key_exists(2, $this->msgList) ? 2
			: (array_key_exists(3, $this->msgList) ? 3 : $this->status);
	}

	public function setBanResubmit($resubmitExpirationMonth=null) {
		if(isset($resubmitExpirationMonth))
			$this->resubmitExpirationMonth = $resubmitExpirationMonth;
		$this->banResubmit = true;
	}

	public function getCanResubmitDate($timestamp=0): string
	{
		$canResubmitDate = '';

		if ($this->banResubmit) {
			$canResubmitDate = new DateTime;
			if ($timestamp)
				$canResubmitDate->setTimestamp($timestamp);
			$canResubmitDate->modify( '+'.$this->resubmitExpirationMonth.' month' );
			$canResubmitDate = $canResubmitDate->format('Y-m-d H:i:s');
		}
		return $canResubmitDate;
	}
}
