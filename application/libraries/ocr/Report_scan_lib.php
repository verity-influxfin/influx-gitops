<?php

class Report_scan_lib
{
    private $endPoints = [
        'balance_sheet' => 'reports/balance-sheets/',
        'balance_sheets' => 'reports/balance-sheets',
        'income_statement' => 'reports/income-statements/',
        'income_statements' => 'reports/income-statements',
        'business_tax_return_report' => 'reports/business-tax-return-reports/',
        'business_tax_return_reports' => 'reports/business-tax-return-reports',
        'insurance_table' => 'reports/insurance-table/',
        'insurance_tables' => 'reports/insurance-tables',
        'amendment_of_register' => 'reports/amendment-of-registers/',
        'amendment_of_registers' => 'reports/amendment-of-registers',
        'credit_investigation' => 'reports/credit-investigations/',
        'credit_investigations' => 'reports/credit-investigations',
    ];

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('S3_upload');
        $this->CI->load->library('S3_lib');
        $this->ocr_url = "http://" . getenv('GRACULA_IP') . ":10001/ocr/api/v1.0/";
    }

    public function requestForScan($type, $image, $ownerId, $ocr_type='')
    {
        if(empty($image) || $ownerId <= 0) {
            return false;
        }

        if($type =='amendment_of_registers' || $type =='amendment_of_register' || $type == 'credit_investigation' || $type == 'credit_investigations' ||  (($type == 'insurance_table'|| $type == 'insurance_tables') && $ocr_type == 'person')){
          foreach($image as $v){
            $newsImageUrl = $this->CI->s3_upload->public_image_by_data(
                file_get_contents($v->url),
                FRONT_S3_BUCKET,
                $ownerId,
                [
                    'type' => 'stmps/ocr_report',
                    'name' => md5($v->url) . '.jpg',
                ]
            );
            $newImageUrl[] = str_replace(INFLUX_S3_URL, FRONT_CDN_URL, $newsImageUrl);
          }
          $url = $this->ocr_url  . $this->endPoints[$type] . "{$image[0]->group_info}";
        }else{
          $newImageUrl = $this->CI->s3_upload->public_image_by_data(
              file_get_contents($image->url),
              FRONT_S3_BUCKET,
              $ownerId,
              [
                  'type' => 'stmps/ocr_report',
                  'name' => md5($image->url) . '.jpg',
              ]
          );
          $newImageUrl = str_replace(INFLUX_S3_URL, FRONT_CDN_URL, $newImageUrl);
          $url = $this->ocr_url  . $this->endPoints[$type] . "{$image->id}";
        }

        if($type =='amendment_of_registers' || $type =='amendment_of_register' || $type == 'credit_investigation' || $type == 'credit_investigations' ||  (($type == 'insurance_table'|| $type == 'insurance_tables') && $ocr_type == 'person')){
          $data = '';
          foreach($newImageUrl as $k=>$v){
            if($k ==0){
              $data .= 'file_url='.$v;
            }else{
              $data .= '&file_url='.$v;
            }
          }
          $data .= '&type='.$ocr_type;
        }else{
          if($type=='insurance_table'||$type=='insurance_tables'){
            $data = ["file_url" => $newImageUrl, "type" => $ocr_type];
          }else{
            $data = ["file_url" => $newImageUrl];
          }
        }

        $this->CI->load->library('utility/http_utility');
        $this->CI->http_utility->setUrl($url);
        $this->CI->http_utility->setBody($data);
        $this->CI->http_utility->setWaitingTime(5000);
        $result = $this->CI->http_utility->put();

        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    public function requestAll($type, $offset, $limit)
    {
        $url = $this->ocr_url  . $this->endPoints[$type];

        if ($offset) {
            $limit = intval($limit) > 0 ? $limit : 20;
            $url .= "?offset={$offset}&limit={$limit}";
        }

        $this->CI->load->library('utility/http_utility');
        $this->CI->http_utility->setUrl($url);
        $this->CI->http_utility->setWaitingTime(200);
        $result = $this->CI->http_utility->get();

        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

    public function requestForResult($type, $imageIds)
    {
        if(!$imageIds) {
            return false;
        }

        $imageIdString = implode(",", $imageIds);
        $url = $this->ocr_url  . $this->endPoints[$type] . "?references={$imageIdString}";

        $this->CI->load->library('utility/http_utility');
        $this->CI->http_utility->setUrl($url);
        $this->CI->http_utility->setWaitingTime(200);
        $result = $this->CI->http_utility->get();

        $response = json_decode($result);

        if (!$result || !isset($response->status) || ($response->status != 200 && $response->status != 204) ) {
            return;
        }

        return $response;
    }

    public function requestForAResult($type, $imageId)
    {
        if(!$imageId) {
            return false;
        }

        $url = $this->ocr_url  . $this->endPoints[$type] . "{$imageId}";

        $this->CI->load->library('utility/http_utility');
        $this->CI->http_utility->setUrl($url);
        $this->CI->http_utility->setWaitingTime(200);
        $result = $this->CI->http_utility->get();

        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }
}
