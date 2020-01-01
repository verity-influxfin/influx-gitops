<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Labor_insurance_lib
{
    const SUCCESS = "success";
    const FAILURE = "failure";
    const PENDING = "pending";

    public function __construct()
    {
		$this->CI = &get_instance();
		$this->CI->load->library('utility/labor_insurance_regex', [], 'regex');
	}

    public function check_labor_insurance($userId, $text, &$result)
    {
        $this->setCurrentTime(time());
        $this->processApplicantDetail($userId, $text, $result);
        $this->processDocumentCorrectness($text, $result);
        $downloadTime = $this->processDocumentIsValid($text, $result);
        $this->processDownloadTimeMatchSearchTime($downloadTime, $text, $result);
        $this->processApplicantHavingLaborInsurance($text, $result);
        $this->processMostRecentCompanyName($text, $result);
        $this->processCurrentJobExperience($text, $result);
        $this->processTotalJobExperience($text, $result);
        $this->processCurrentSalary($text, $result);
        $this->processApplicantServingWithTopCompany($text, $result);
        $this->processApplicantHavingGreatJob($text, $result);
        $this->processApplicantHavingGreatSalary($text, $result);
        $this->aggregate($result);

        return $result;
    }

    public function processDocumentCorrectness($text, &$result)
    {
        $message = [
            "stage" => "correctness",
            "status" => self::FAILURE,
            "message" => ""
        ];

        $isApplication = $this->CI->regex->isLaborInsuranceApplication($text);
        if ($isApplication) {
            $message["status"] = self::SUCCESS;
            $result["messages"][] = $message;
            return;
        }

        $message["status"] = self::FAILURE;
        $message["message"] = "上傳文件錯誤";
        $result["messages"][] = $message;
    }

    public function processDocumentIsValid($text, &$result)
    {
        $message = [
            "stage" => "download_time",
            "status" => self::PENDING,
            "message" => "無法辨識日期"
        ];

        $content = $this->CI->regex->findPatternInBetween($text, "網頁下載時間", "秒");
        if (!$content) {
            $result["messages"][] = $message;
            return;
        }

        $downloadTimeText = $content[0];

        $downloadTimeArray = $this->CI->regex->extractDownloadTime($downloadTimeText);
        if (!$downloadTimeArray || !is_array($downloadTimeArray[0]) || count($downloadTimeArray[0]) != 6) {
            $result["messages"][] = $message;
            return;
        }

        $downloadTime = $this->convertDownloadTimeToTimestamp($downloadTimeArray[0]);

        $mustAfter = $this->currentTime - 31 * 86400;
        if ($downloadTime < $mustAfter || $downloadTime > $this->currentTime) {
            $message["status"] = self::FAILURE;
            $message["message"] = "勞保異動明細非一個月內";
            $result["messages"][] = $message;
            return;
        }

        $message["status"] = self::SUCCESS;
        $message["message"] = "";
        $result["messages"][] = $message;
        return $downloadTime;
    }

    public function processDownloadTimeMatchSearchTime($downloadTime, $text, &$result)
    {
        $message = [
            "stage" => "time_matches",
            "status" => self::PENDING,
            "message" => "無法辨識日期"
        ];

        $content = $this->CI->regex->findPatternInBetween($text, "查詢日期起訖：", "【");
        if (!$content) {
            $result["messages"][] = $message;
            return;
        }

        $searchTimeArray = $this->CI->regex->extractDownloadTime($content[0]);

        if (
            !is_array($searchTimeArray)
            || !$searchTimeArray
            || !$searchTimeArray[0]
            || !is_array($searchTimeArray[0])
        ) {
            $result["messages"][] = $message;
            return;
        }

        if (count($searchTimeArray) == 2) {
            $message["status"] = self::FAILURE;
            $message["message"] = "勞保異動明細非歷年";
            $result["messages"][] = $message;
            return;
        }

        $endTime = $searchTimeArray[0][0];

        $searchTime = $this->convertSearchTimeToTimestamp($endTime);
        if ($downloadTime != $searchTime) {
            $message["status"] = self::FAILURE;
            $message["message"] = "勞保異動明細非歷年";
            $result["messages"][] = $message;
            return;
        }

        $message["status"] = self::SUCCESS;
        $message["message"] = "";
        $result["messages"][] = $message;
    }

    private function convertDownloadTimeToTimestamp(array $downloadTimeArray)
    {
        $year = intval($downloadTimeArray[0]) + 1911;
        $month = intval($downloadTimeArray[1]);
        $day = intval($downloadTimeArray[2]);

        return strtotime("{$year}-{$month}-{$day}");
    }

    private function convertSearchTimeToTimestamp(string $searchTime)
    {
        $totalLength = strlen($searchTime);
        $dayStart = $totalLength - 2;
        $monthStart = $dayStart - 2;
        $year = mb_substr($searchTime, 0, 3) + 1911;
        $month = mb_substr($searchTime, $monthStart, 2);
        $day = mb_substr($searchTime, $dayStart, 2);

        return strtotime("{$year}-{$month}-{$day}");
    }

    public function processApplicantDetail($userId, $text, &$result)
    {

    }

    public function processApplicantHavingLaborInsurance($text, &$result)
    {

    }

    public function processMostRecentCompanyName($text, &$result)
    {

    }

    public function processCurrentJobExperience($text, &$result)
    {

    }

    public function processTotalJobExperience($text, &$result)
    {

    }

    public function processCurrentSalary($text, &$result)
    {

    }

    public function processApplicantServingWithTopCompany($text, &$result)
    {

    }

    public function processApplicantHavingGreatJob($text, &$result)
    {

    }

    public function processApplicantHavingGreatSalary($text, &$result)
    {

    }

    public function aggregate(&$result)
    {

    }

    public function setCurrentTime($currentTime)
    {
		$this->currentTime = $currentTime;
	}
}