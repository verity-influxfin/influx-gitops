<?php

class Loan_table_output
{
    protected $alias;
    protected $table;

    public function __construct($params)
    {
        if (!isset($params["data"]) || !isset($params["alias"])) {
            throw new OutOfBoundsException("Data to construct loan table output is not found");
        }

        $this->alias = $params['alias'];
        $this->table = $params['data'];
        $this->CI = &get_instance();
    }

    public function toOne()
    {
        if (!$this->table) {
            return [];
        }
        return $this->map($this->table, $this->alias);
    }

    public function map($table, $alias)
    {
        $newOfficeWorkerOutput = "{$alias}_new_office_worker_output";
        $existingOfficeWorkers = "{$alias}_existing_office_worker_output";
        $newStudents = "{$alias}_new_student_output";
        $existingStudents = "{$alias}_existing_student_output";
        $total = "{$alias}_total_output";

        $this->CI->load->library('output/report/loan/loan_row_output', ["data" => $table->getNewOfficeWorkers()], $newOfficeWorkerOutput);
        $this->CI->load->library('output/report/loan/loan_row_output', ["data" => $table->getExistingOfficeWorkers()], $existingOfficeWorkers);
        $this->CI->load->library('output/report/loan/loan_row_output', ["data" => $table->getNewStudents()], $newStudents);
        $this->CI->load->library('output/report/loan/loan_row_output', ["data" => $table->getExistingStudents()], $existingStudents);
        $this->CI->load->library('output/report/loan/loan_row_output', ["data" => $table->getTotal()], $total);

        return [
            'new_office_workers' => $this->CI->$newOfficeWorkerOutput->toOne(),
            'existing_office_workers' => $this->CI->$existingOfficeWorkers->toOne(),
            'new_students' => $this->CI->$newStudents->toOne(),
            'existing_students' => $this->CI->$existingStudents->toOne(),
            'total' => $this->CI->$total->toOne(),
        ];
    }
}