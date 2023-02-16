<?php

namespace CertificationResult;
defined('BASEPATH') or exit('No direct script access allowed');

class IdentityCertificationResult extends CertificationResult
{
    public static $FAILED_MESSAGE = '親愛的會員您好，為確保資料真實性，請您提重新提供實名認證資料，更新您的訊息，謝謝。';
    public static $RIS_CHECK_FAILED_MESSAGE = '身分證資訊驗證失敗';
    public static $RIS_NO_RESPONSE_MESSAGE = '戶役政無回應';

    public function __construct($status, $resubmitExpirationMonth = 6)
    {
        parent::__construct($status, $resubmitExpirationMonth);
    }

    public function getAPPMessage($status): array
    {
        if ($this->banResubmit)
            return [self::$FAILED_MESSAGE];
        else
            return $this->getMessage($status, MessageDisplay::Client);
    }
}
