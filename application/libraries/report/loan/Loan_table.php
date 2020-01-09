<?php

class Loan_table
{
    /**
     * @var LoanRow
     */
    protected $newOfficeWorkers;

    /**
     * @var LoanRow
     */
    protected $existingOfficeWorkers;

    /**
     * @var LoanRow
     */
    protected $newStudents;

    /**
     * @var LoanRow
     */
    protected $existingStudents;

    /**
     * @var LoanRow
     */
    protected $total;
    
    public function __construct($params)
    {
        $this->CI = &get_instance();
        
        $type = isset($params['type']) ? $params['type'] : '';
        
        $newOfficeWorkerType = "{$type}_new_office_worker_row";
        $existingOfficeWorkerType = "{$type}_existing_office_worker_row";
        $newStudentType = "{$type}_new_student_row";
        $existingStudentType = "{$type}_existing_student_row";
        $loanTotalType = "{$type}_loan_total_row";
        $this->CI->load->library('report/loan/loan_row', [], $newOfficeWorkerType);
        $this->CI->load->library('report/loan/loan_row', [], $existingOfficeWorkerType);
        $this->CI->load->library('report/loan/loan_row', [], $newStudentType);
        $this->CI->load->library('report/loan/loan_row', [], $existingStudentType);
        $this->CI->load->library('report/loan/loan_row', [], $loanTotalType);
        $this->newOfficeWorkers = $this->CI->$newOfficeWorkerType;
        $this->existingOfficeWorkers = $this->CI->$existingOfficeWorkerType;
        $this->newStudents = $this->CI->$newStudentType;
        $this->existingStudents = $this->CI->$existingStudentType;
        $this->total = $this->CI->$loanTotalType;
    }

    /**
     * Get the value of New Office Workers
     *
     * @return LoanRow
     */
    public function getNewOfficeWorkers()
    {
        return $this->newOfficeWorkers;
    }

    /**
     * Set the value of New Office Workers
     *
     * @param LoanRow $newOfficeWorkers
     *
     * @return self
     */
    public function setNewOfficeWorkers(LoanRow $newOfficeWorkers)
    {
        $this->newOfficeWorkers = $newOfficeWorkers;

        return $this;
    }

    /**
     * Get the value of Existing Office Workers
     *
     * @return LoanRow
     */
    public function getExistingOfficeWorkers()
    {
        return $this->existingOfficeWorkers;
    }

    /**
     * Set the value of Existing Office Workers
     *
     * @param LoanRow $existingOfficeWorkers
     *
     * @return self
     */
    public function setExistingOfficeWorkers(LoanRow $existingOfficeWorkers)
    {
        $this->existingOfficeWorkers = $existingOfficeWorkers;

        return $this;
    }

    /**
     * Get the value of New Students
     *
     * @return LoanRow
     */
    public function getNewStudents()
    {
        return $this->newStudents;
    }

    /**
     * Set the value of New Students
     *
     * @param LoanRow $newStudents
     *
     * @return self
     */
    public function setNewStudents(LoanRow $newStudents)
    {
        $this->newStudents = $newStudents;

        return $this;
    }

    /**
     * Get the value of Existing New Students
     *
     * @return LoanRow
     */
    public function getExistingStudents()
    {
        return $this->existingStudents;
    }

    /**
     * Set the value of Existing New Students
     *
     * @param LoanRow $existingStudents
     *
     * @return self
     */
    public function setExistingStudents(LoanRow $existingStudents)
    {
        $this->existingStudents = $existingStudents;

        return $this;
    }

    /**
     * Get the value of Total
     *
     * @return LoanRow
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of Total
     *
     * @param LoanRow $total
     *
     * @return self
     */
    public function setTotal(LoanRow $total)
    {
        $this->total = $total;

        return $this;
    }
}