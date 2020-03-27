<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Judicial_yuan_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $judicialYuanServerPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$judicialYuanServerPort}/scraper/api/v1.0/";
    }

    public function mappingAddressAndScraperAddress($address)
    {
        $reference = [
          '宜縣' => '宜蘭', '竹縣' => '新竹', '苗縣' => '苗栗', '中縣' => '臺中', '彰縣' => '彰化', '投縣' => '南投', '雲縣' => '雲林', '嘉縣' => '嘉義',
          '南縣' => '臺南', '高縣' => '高雄', '屏縣' => '屏東', '東縣' => '臺東', '花縣' => '花蓮', '澎縣' => '澎湖', '基市' => '基隆', '竹市' => '新竹',
          '嘉市' => '嘉義', '連江' => '連江', '金門' => '金門', '北市' => '臺北', '高市' => '高雄', '新北市'=> '新北', '中市' => '臺中', '南市' => '臺南',
          '桃市' => '桃園'
        ];

        if( isset($reference[$address]) ){
          return $reference[$address];
        }

        return $address;
    }

    public function requestJudicialYuanVerdicts($name, $address, $reference)
    {
        if(!$name || !$address || !$reference) {
            return false;
        }

        $url = $this->scraperUrl  . "verdicts";

        $data = ["query" => $name, "location" => $this->mappingAddressAndScraperAddress($address), "reference" => $reference];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    public function requestJudicialYuanVerdictsStatuses($reference){
      if(!$reference){
        return;
      }

      $url = $this->scraperUrl  . "verdicts/{$reference}/statuses";

      $result = curl_get($url);

      $response = json_decode($result, true);

      return $response;
    }

    public function requestJudicialYuanVerdictsCount($name){
      if(!$name){
        return;
      }

      $url = $this->scraperUrl  . "verdicts/{$name}/count";

      $result = curl_get($url);

      $response = json_decode($result, true);

      return $response;
    }
}
