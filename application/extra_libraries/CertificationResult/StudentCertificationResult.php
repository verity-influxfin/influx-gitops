<?php

namespace CertificationResult;
defined('BASEPATH') or exit('No direct script access allowed');

class StudentCertificationResult extends CertificationResult
{
    public static $FAILED_MESSAGE = '未在有效時間內完成認證。';

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
