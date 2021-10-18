<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Judicial_yuan_lib
{
    function __construct($params=[])
    {
        $this->CI = &get_instance();
        $judicialYuanServerPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$judicialYuanServerPort}/scraper/api/v1.0/";
        if(isset($params['ip'])){
          $this->scraperUrl = "http://{$params['ip']}/scraper/api/v1.0/";
        }
    }

    public function mappingAddressAndScraperAddress($address)
    {
        $address = preg_replace('/\(|\).*/',"",$address);

        $reference = [
          '宜縣' => '宜蘭', '竹縣' => '新竹', '苗縣' => '苗栗', '中縣' => '臺中', '彰縣' => '彰化', '投縣' => '南投', '雲縣' => '雲林', '嘉縣' => '嘉義',
          '南縣' => '臺南', '高縣' => '高雄', '屏縣' => '屏東', '東縣' => '臺東', '花縣' => '花蓮', '澎縣' => '澎湖', '基市' => '基隆', '竹市' => '新竹',
          '嘉市' => '嘉義', '連江' => '連江', '金門' => '金門', '北市' => '臺北', '高市' => '高雄', '新北市'=> '新北', '中市' => '臺中', '南市' => '臺南',
          '桃市' => '桃園'
        ];

        if( isset($reference[$address]) ){
          return $reference[$address];
        }

        if(strlen($address) > 20 && (strpos($address,'市')|| strpos($address,'縣')) ){
          if(strpos($address,'市')){
            return substr($address, 0, strpos($address,'市'));
          }
          if(strpos($address,'縣')){
            return substr($address, 0, strpos($address,'縣'));
          }
        }

        return $address;
    }

    public function mappingStatusToChinese($status)
    {
        $statusName = [
          'finished' => '爬蟲執行完成', 'requested' => '爬蟲尚未開始', 'started' => '爬蟲正在執行中', 'fail' => '爬蟲執行失敗'
        ];

        if( isset($statusName[$status]) ){
          return $statusName[$status];
        }

        return $status;
    }

    public function requestJudicialYuanVerdicts($name, $address, $reference)
    {
        if(!$name || !$address || !$reference) {
            return false;
        }
        $response = [];
        $url = $this->scraperUrl  . "verdicts";

        $address = $this->mappingAddressAndScraperAddress($address);

        $data = ["query" => $name, "location" => $address, "reference" => $reference];

        $result = curl_get($url, $data);
        if($result){
            $response = json_decode($result,true);
        }else{
            return false;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsStatuses($reference){
      if(!$reference){
        return;
      }
      $response = [];
      $url = $this->scraperUrl  . "verdicts/{$reference}/statuses";

      $result = curl_get($url);

      if($result){
        $response = json_decode($result, true);
      }

      if(isset($response['response']['status'])){
        $response['response']['status'] = $this->mappingStatusToChinese($response['response']['status']);
      }

      return $response;
    }

    public function requestJudicialYuanVerdictsCount($name){
      if(!$name){
        return;
      }
      $response = [];
      $url = $this->scraperUrl  . "verdicts/{$name}/count";

      $result = curl_get($url);

      $response = json_decode($result, true);

      return $response;
    }

    public function requestJudicialYuanVerdictsCase($name, $case, $page){
      if(!$name || !$case || !$page){
        return;
      }
      $response = [];
      $url = $this->scraperUrl  . "verdicts/{$name}/case?type={$case}&page={$page}";

      $result = curl_get($url);

      if($result){
          $response = json_decode($result, true);
      }

      return $response;
    }
}
