<?php

class StudentCertificationResult extends CertificationResult
{
	public function __construct($status, $resubmitExpirationMonth=6)
	{
		parent::__construct($status, $resubmitExpirationMonth);
	}

	public function getAPPMessage($status): array {
		if($this->banResubmit)
			return ['經AI系統綜合評估後，暫時無法核准您的申請，感謝您的支持與愛護，希望下次還有機會為您服務'];
		else
			return $this->getMessage($status, MassageDisplay::Client);
	}
}
