<?php

namespace Certification_ocr\Marker;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification_ocr\Certification_ocr_base;

/**
 * OCR 辨識
 * Type : 標記圖片上的關鍵訊息
 */
abstract class Ocr_marker_base extends Certification_ocr_base
{
    public function __construct($certification)
    {
        $this->api_url = 'http://' . getenv('CERT_OCR_MARKER_IP') . ':' . $this->get_ocr_port();
        parent::__construct($certification);
    }

    /**
     * 取得 OCR 子系統 port
     * @return string
     */
    protected function get_ocr_port(): string
    {
        return getenv('CERT_OCR_MARKER_PORT');
    }
}