<?php

class Loan_row_output
{
    protected $row;

    public function __construct($params)
    {
        if (!isset($params["data"])) {
            throw new OutOfBoundsException("Data to construct loan table output is not found");
        }

        $this->row = $params['data'];;
        $this->CI = &get_instance();
    }

    public function toOne()
    {
        if (!$this->row) {
            return [];
        }
        return $this->map($this->row);
    }

    public function map($row)
    {
        return [
            'applicants' => intval($row->getApplicants()),
            'pending_signing_applicants' => intval($row->getPendingSigningApplicants()),
            'on_the_market' => intval($row->getOnTheMarket()),
            'matched_applicants' => intval($row->getMatchedApplicants()),
            'match_rate' => intval($row->getMatchRate()),
            'applications' => intval($row->getApplications()),
            'matched_applications' => intval($row->getMatchedApplications()),
            'approved_pending_signing_amount' => intval($row->getApprovedPendingSigningAmount()),
            'on_the_market_amount' => intval($row->getOnTheMarketAmount()),
            'matched_amount' => intval($row->getMatchedAmount()),
        ];
    }
}