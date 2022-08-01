<?php

namespace Certification_ocr\Parser;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification_ocr\Certification_ocr_base;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 */
abstract class Ocr_parser_base extends Certification_ocr_base
{
    public function __construct($certification)
    {
        $this->api_url = 'http://' . getenv('CERT_OCR_IP') . ':' . getenv('CERT_OCR_PORT');
        parent::__construct($certification);
    }
}