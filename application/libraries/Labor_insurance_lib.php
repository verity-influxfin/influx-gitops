<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Labor_insurance_lib
{
    const SUCCESS = "success";
    const FAILURE = "failure";
    const PENDING = "pending";

    const MINIMUM_WAGE = 23800;

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
        $rows = $this->readRows($text);
        $this->processApplicantHavingLaborInsurance($rows, $result);
        $this->processMostRecentCompanyName($rows, $result);
        $this->processCurrentJobExperience($text, $result);
        $this->processTotalJobExperience($text, $result);
        $this->processCurrentSalary($rows, $result);
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

        $searchTime = $this->convertTaiwanTimeToTime($endTime);
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

    private function convertTaiwanTimeToTime(string $searchTime)
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
        $message = [
            "stage" => "applicant_detail",
            "status" => self::PENDING,
            "message" => ""
        ];

        $idNumberMatch = $this->CI->regex->extractIdNumber($text);
        if (!$idNumberMatch) {
            $message["message"] = "身分證字號無法判讀";
            $result["messages"][] = $message;
            return;
        }
        $idNumber = $idNumberMatch[0];

        $fullnameText = $this->CI->regex->findNonGreedyPatternInBetween($text, "姓名：", "出生日期");
        if (!$fullnameText || !trim($fullnameText[0])) {
            $message["message"] = "姓名無法判讀";
            $result["messages"][] = $message;
            return;
        }
        $fullname = trim($fullnameText[0]);

        $bornAtText = $this->CI->regex->findNonGreedyPatternInBetween($text, "出生日期：", "查詢日期起訖");
        if (!$bornAtText || !trim($bornAtText[0])) {
            $message["message"] = "出生日期無法判讀";
            $result["messages"][] = $message;
            return;
        }
        $bornAt = trim($bornAtText[0]);
        $bornAt = $this->convertTaiwanTimeToTime($bornAt);

        $user = $this->CI->user_model->get($userId);

        if (
            $user->id_number == $idNumber
            && $user->name == $fullname
            && strtotime($user->birthday) == $bornAt
        ) {
            $message["status"] = self::SUCCESS;
            $message["message"] = "";
            $result["messages"][] = $message;
            return;
        }

        $message["status"] = self::FAILURE;
        $message["message"] = "勞保異動明細非本人";
        $message["rejected_message"] = "請您提供本人近一個月內最新的勞工保險異動明細資料";
        $result["messages"][] = $message;
    }

    public function processApplicantHavingLaborInsurance($rows, &$result)
    {
        $message = [
            "stage" => "insurance_enrollment",
            "status" => self::PENDING,
            "message" => ""
        ];
        if (!$rows) {
            $message['status'] = self::FAILURE;
            $message["message"] = "未加保勞保";
            $message["rejected_message"] = "系統無法清楚確認您的工作證明，感謝您的支持與愛護，希望下次還有機會為您服務。";
            $result["messages"][] = $message;
            return;
        }

        foreach ($rows as $company => $records) {
            if (!$records) {
                $message["status"] = self::PENDING;
                $message["message"] = "讀取資料失敗";
                $result["messages"][] = $message;
                return;
            }

            $numRecords = count($records);
            $lastIndex = $numRecords - 1;
            $record = $records[$lastIndex];

            if (!isset($record['endAt'])) {
                $message["status"] = self::SUCCESS;
                $message["message"] = "";
                $result["messages"][] = $message;
                return;
            }
        }

        $message['status'] = self::FAILURE;
        $message["message"] = "未加保勞保";
        $message["rejected_message"] = "系統無法清楚確認您的工作證明，感謝您的支持與愛護，希望下次還有機會為您服務。";
        $result["messages"][] = $message;
    }

    public function readRows($text)
    {
        $text = $this->CI->regex->replaceSpacesToSpace($text);
        $data = explode(" ", $text);

        $rows = [];
        $row = [];
        $total = count($data);
        $currentIndex = 0;
        $numAdded = 0;
        for ($i = 0; $i < $total; $i++) {
            $each = $data[$i];
            $insuranceId = $this->CI->regex->isInsuranceId($each);
            if ($insuranceId) {
                if ($row) {
                    $rows[$row['name']][] = $row;
                    $currentIndex = 0;
                    $numAdded++;
                    $row = [];
                }
                $row['index'] = $numAdded;
                $row['id'] = $each;
                $currentIndex = 2;
                continue;
            }

            if ($currentIndex > 0) {
                $isNumber = is_numeric($each);
                $isTimeFormat = strlen($each) == 7 && is_numeric($each);
                if ($currentIndex == 2) {
                    $row['name'] = $each;
                    $currentIndex++;
                    continue;
                }
                if ($currentIndex == 3) {
                    $isSalary = $this->CI->regex->isSalary($each);
                    if ($isSalary) {
                        $row['salary'] = $each;
                        $currentIndex++;
                        continue;
                    } elseif ($isTimeFormat) {
                        $row['createdAt'] = $each;
                        $currentIndex++;
                        continue;
                    } else {
                        $row['name'] .= $each;
                    }
                    if (!isset($rows[$row['name']])) {
                        $rows[$row['name']] = [];
                    }
                }
                if ($currentIndex == 4) {
                    $isSalary = $this->CI->regex->isSalary($each);
                    if ($isTimeFormat && !isset($row['createdAt'])) {
                        $row['createdAt'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if ($isTimeFormat && isset($row['createdAt'])) {
                        $row['endAt'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if (!$isNumber && isset($row['createdAt'])) {
                        $row['comment'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if ($each == "F" || $each == "D") {
                        $row["overdue"] = $each;
                    }
                }

                if ($currentIndex == 5) {
                    if ($isTimeFormat && isset($row['createdAt'])) {
                        $row['endAt'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if (!$isNumber && isset($row['createdAt']) && !isset($row['comment'])) {
                        $row['comment'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if ($each == "F" || $each == "D") {
                        $row["overdue"] = $each;
                    }
                }

                if ($currentIndex == 6) {
                    if (!$isNumber && isset($row['createdAt']) && !isset($row['comment'])) {
                        $row['comment'] = $each;
                        $currentIndex++;
                        continue;
                    }
                    if ($each == "F" || $each == "D") {
                        $row["overdue"] = $each;
                        $currentIndex++;
                        continue;
                    }
                }
                if ($currentIndex == 7 && in_array($each, ["F", "D"])) {
                    $row["overdue"] = $each;
                    $currentIndex++;
                    continue;
                }
            }
        }
        if ($row) {
            $rows[$row['name']][] = $row;
        }
        return $rows;
    }

    public function processMostRecentCompanyName($rows, &$result)
    {
        $message = [
            "stage" => "company",
            "status" => self::PENDING,
            "message" => ""
        ];

        $enrolledInsurance = null;
        foreach ($rows as $company => $records) {
            $numRecords = count($records);
            $lastIndex = $numRecords - 1;
            $record = $records[$lastIndex];

            if (!isset($record['endAt'])) {
                if (!$enrolledInsurance) {
                    $enrolledInsurance = $record;
                } else {
                    $message['status'] = self::PENDING;
                    $message["message"] = "多家投保";
                    $result["messages"][] = $message;
                    return;
                }
            }
        }

        if (!$enrolledInsurance) {
            $message["status"] = self::FAILURE;
            $message["message"] = "未發現任何仍在加保中的公司名稱";
            $result["messages"][] = $message;
            return;
        }

        if (isset($enrolledInsurance["comment"]) && strpos($enrolledInsurance['comment'], "不適用就業保險") !== false) {
            $message["status"] = self::FAILURE;
            $message["message"] = "不符合平台規範";
            $message["rejected_message"] = "經平台綜合評估暫時無法核准您的工作認證，感謝您的支持與愛護，希望下次還有機會為您服務。";
            $result["messages"][] = $message;
            return;
        }

        if (isset($enrolledInsurance["overdue"])) {
            $message["status"] = self::FAILURE;
            $message["message"] = "不符合平台規範";
            $message["rejected_message"] = "經平台綜合評估暫時無法核准您的工作認證，感謝您的支持與愛護，希望下次還有機會為您服務。";
            $result["messages"][] = $message;
            return;
        }

        if (
            strpos($enrolledInsurance['name'], '工會') !== false
            || strpos($enrolledInsurance['name'], '漁會') !== false
        ) {
            $message["status"] = self::PENDING;
            $message["message"] = "加保在公會、漁會";
            $result["messages"][] = $message;
            return;
        }

        $message["status"] = self::SUCCESS;
        $message["message"] = "公司 : " . $enrolledInsurance["name"];
        $result["messages"][] = $message;
    }

    public function processCurrentJobExperience($text, &$result)
    {

    }

    public function processTotalJobExperience($text, &$result)
    {

    }

    public function processCurrentSalary($rows, &$result)
    {
        $message = [
            "stage" => "salary",
            "status" => self::PENDING,
            "message" => ""
        ];

        $enrolledInsurance = null;
        foreach ($rows as $company => $records) {
            $numRecords = count($records);
            $lastIndex = $numRecords - 1;
            $record = $records[$lastIndex];

            if (!isset($record['endAt'])) {
                if (!$enrolledInsurance) {
                    $enrolledInsurance = $record;
                } else {
                    $message['status'] = self::PENDING;
                    $message['message'] = "多家投保";
                    $result['messages'][] = $message;
                    return;
                }
            }
        }

        $salary = 0;
        if (isset($enrolledInsurance['salary'])) {
            $salary = $this->CI->regex->convertSalary($enrolledInsurance['salary']);
        }

        if ($salary >= self::MINIMUM_WAGE) {
            $message['status'] = self::SUCCESS;
            $roundSalary = round($salary, -3);
            $roundSalary = $roundSalary >= $salary ? $roundSalary : $roundSalary + 1000;
            $message['message'] = "投保月薪 : " . $roundSalary;
            $result['messages'][] = $message;
            return;
        }

        $message['status'] = self::PENDING;
        $result['messages'][] = $message;
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
        if (!$result) {
			$result = ["status" => "pending", "messages" => []];
		}
		foreach ($result["messages"] as $stage) {
			if (!$result["status"]) {
				$result["status"] = "success";
			}
			if ($stage["status"] == "failure") {
				$result["status"] = "failure";
			}
			if ($stage["status"] == "pending" && $result["status"] == "success") {
				$result["status"] = "pending";
			}
		}
		return $result;
    }

    public function setCurrentTime($currentTime)
    {
		$this->currentTime = $currentTime;
	}
}