<?php

class Loan_row
{
    /**
     * @var int
     */
    protected $applicants;

    /**
     * @var int
     */
    protected $pendingSigningApplicants;

    /**
     * @var int
     */
    protected $onTheMarket;

    /**
     * @var int
     */
    protected $matchedApplicants;

    /**
     * @var int
     */
    protected $matchRate;

    /**
     * @var int
     */
    protected $applications;

    /**
     * @var int
     */
    protected $matchedApplications;

    /**
     * @var int
     */
    protected $approvedPendingSigningAmount;

    /**
     * @var int
     */
    protected $onTheMarketAmount;

    /**
     * @var int
     */
    protected $matchedAmount;

    /**
     * Get the value of Applicants
     *
     * @return int
     */
    public function getApplicants()
    {
        return $this->applicants;
    }

    /**
     * Set the value of Applicants
     *
     * @param int $applicants
     *
     * @return self
     */
    public function setApplicants($applicants)
    {
        $this->applicants = $applicants;

        return $this;
    }

    /**
     * Get the value of Pending Signing Applicants
     *
     * @return int
     */
    public function getPendingSigningApplicants()
    {
        return $this->pendingSigningApplicants;
    }

    /**
     * Set the value of Pending Signing Applicants
     *
     * @param int $pendingSigningApplicants
     *
     * @return self
     */
    public function setPendingSigningApplicants($pendingSigningApplicants)
    {
        $this->pendingSigningApplicants = $pendingSigningApplicants;

        return $this;
    }

    /**
     * Get the value of On The Market
     *
     * @return int
     */
    public function getOnTheMarket()
    {
        return $this->onTheMarket;
    }

    /**
     * Set the value of On The Market
     *
     * @param int $onTheMarket
     *
     * @return self
     */
    public function setOnTheMarket($onTheMarket)
    {
        $this->onTheMarket = $onTheMarket;

        return $this;
    }

    /**
     * Get the value of Matched Applicants
     *
     * @return int
     */
    public function getMatchedApplicants()
    {
        return $this->matchedApplicants;
    }

    /**
     * Set the value of Matched Applicants
     *
     * @param int $matchedApplicants
     *
     * @return self
     */
    public function setMatchedApplicants($matchedApplicants)
    {
        $this->matchedApplicants = $matchedApplicants;

        return $this;
    }

    /**
     * Get the value of Match Rate
     *
     * @return int
     */
    public function getMatchRate()
    {
        return $this->matchRate;
    }

    /**
     * Set the value of Match Rate
     *
     * @param int $matchRate
     *
     * @return self
     */
    public function setMatchRate($matchRate)
    {
        $this->matchRate = $matchRate;

        return $this;
    }

    /**
     * Get the value of Applications
     *
     * @return int
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set the value of Applications
     *
     * @param int $applications
     *
     * @return self
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;

        return $this;
    }

    /**
     * Get the value of Matched Applications
     *
     * @return int
     */
    public function getMatchedApplications()
    {
        return $this->matchedApplications;
    }

    /**
     * Set the value of Matched Applications
     *
     * @param int $matchedApplications
     *
     * @return self
     */
    public function setMatchedApplications($matchedApplications)
    {
        $this->matchedApplications = $matchedApplications;

        return $this;
    }

    /**
     * Get the value of Approved Pending Signing Amount
     *
     * @return int
     */
    public function getApprovedPendingSigningAmount()
    {
        return $this->approvedPendingSigningAmount;
    }

    /**
     * Set the value of Approved Pending Signing Amount
     *
     * @param int $approvedPendingSigningAmount
     *
     * @return self
     */
    public function setApprovedPendingSigningAmount($approvedPendingSigningAmount)
    {
        $this->approvedPendingSigningAmount = $approvedPendingSigningAmount;

        return $this;
    }

    /**
     * Get the value of On The Market Amount
     *
     * @return int
     */
    public function getOnTheMarketAmount()
    {
        return $this->onTheMarketAmount;
    }

    /**
     * Set the value of On The Market Amount
     *
     * @param int $onTheMarketAmount
     *
     * @return self
     */
    public function setOnTheMarketAmount($onTheMarketAmount)
    {
        $this->onTheMarketAmount = $onTheMarketAmount;

        return $this;
    }

    /**
     * Get the value of Matched Amount
     *
     * @return int
     */
    public function getMatchedAmount()
    {
        return $this->matchedAmount;
    }

    /**
     * Set the value of Matched Amount
     *
     * @param int $matchedAmount
     *
     * @return self
     */
    public function setMatchedAmount($matchedAmount)
    {
        $this->matchedAmount = $matchedAmount;

        return $this;
    }

}