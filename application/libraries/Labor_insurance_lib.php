<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Labor_insurance_lib
{
    public function check_labor_insurance($userId, $text, &$result)
    {
        $this->processApplicantDetail($userId, $text, $result);
        $this->processDocumentCorrectness($text, $result);
        $this->processDocumentIsValid($text, $result);
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

    }

    public function processDocumentIsValid($text, &$result)
    {

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
}