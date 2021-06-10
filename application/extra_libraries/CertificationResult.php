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
		$result = array_filter($this->msgList, function ($msg) use ($showFlag) {
			return $msg[1] <= $showFlag;
		});
		return array_values($result)[0];
	}

	public function getAllMessage($showFlag=MassageDisplay::All): array
	{
		$allMsg = call_user_func_array('array_merge', $this->msgList);
		$result = array_filter($allMsg, function ($msg) use ($showFlag) {
			return $msg[1] <= $showFlag;
		});
		return array_unique(array_column($result, 0));
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
			: array_key_exists(3, $this->msgList) ? 3 : $this->status;
	}

	public function setBanResubmit($resubmitExpirationMonth=null) {
		if(isset($resubmitExpirationMonth))
			$this->resubmitExpirationMonth = $resubmitExpirationMonth;
		$this->banResubmit = true;
	}

	public function getCanResubmitDate(): string
	{
		$canResubmitDate = '';

		if ($this->banResubmit) {
			$canResubmitDate = new DateTime;
			$canResubmitDate->modify( '+'.$this->resubmitExpirationMonth.' month' );
			$canResubmitDate = $canResubmitDate->format('Y-m-d H:i:s');
		}
		return $canResubmitDate;
	}
}
