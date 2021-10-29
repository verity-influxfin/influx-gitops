<?php

class Job_credit_output
{
    protected $job_credits;
    protected $certification;

    public function __construct($params)
    {
        if (!isset($params["data"])) {
            throw new OutOfBoundsException("Data to construct job credit output is not found");
        }

        $this->job_credits = $params["data"];
        if (isset($params["certification"])) {
            $this->certification = $params["certification"];
        }
    }

    public function toOne()
    {
        return $this->map($this->job_credits);
    }

    public function map($job_credits, $withSensitiveInfo = false)
    {
        $output = [
            "status" => '',
            "messages" => '',
        ];

        $output['status'] = $job_credits ? $job_credits->status : '';
        $output['messages'] = $job_credits ? $this->mapMessages($job_credits->messages) : '';

        if (isset($this->certification->status)) {
            if ($this->certification->status == 1) {
                $output["status"] = "success";
            } elseif ($this->certification->status == 2) {
                $output["status"] = "failure";
            } elseif ($this->certification->status == 3) {
                $output["status"] = "pending";
            }
        }

        $output['scan_status'] = $job_credits ? 'finished' : 'wait_scan';

        if (isset($this->certification->content->license_status)) {
            $output["license_status"] = $this->certification->content->license_status;
        } else {
            $output["license_status"] = 0;
        }

        if (isset($this->certification->content->pro_level)) {
            $output["pro_level"] = $this->certification->content->pro_level;
        } else {
            $output["pro_level"] = 0;
        }

        if (isset($this->certification->content->industry)) {
			$output["industry"] = $this->certification->content->industry;
        } else {
			$output["industry"] = 0;
        }

        if (isset($this->certification->content->company)) {
			$output["company"] = $this->certification->content->company;
        } else {
			$output["company"] = 0;
        }

        if (isset($this->certification->content->tax_id)) {
			$output["tax_id"] = $this->certification->content->tax_id;
        } else {
			$output["tax_id"] = 0;
        }

        if (isset($this->certification->content->industry)) {
			$output["industry"] = $this->certification->content->industry;
        } else {
			$output["industry"] = 0;
        }

        if (isset($this->certification->content->job_title)) {
			$output["job_title"] = $this->certification->content->job_title;
        } else {
			$output["job_title"] = '未填';
        }

        if (isset($this->certification->content->employee)) {
			$output["employee"] = $this->certification->content->employee;
        } else {
			$output["employee"] = 0;
        }

        if (isset($this->certification->content->position)) {
			$output["position"] = $this->certification->content->position;
        } else {
			$output["position"] = 0;
        }

        if (isset($this->certification->content->type)) {
			$output["type"] = $this->certification->content->type;
        } else {
			$output["type"] = 0;
        }

        if (isset($this->certification->content->seniority)) {
			$output["seniority"] = $this->certification->content->seniority;
        } else {
			$output["seniority"] = 0;
        }

        if (isset($this->certification->content->job_seniority)) {
			$output["job_seniority"] = $this->certification->content->job_seniority;
        } else {
			$output["job_seniority"] = 0;
        }

        if (isset($this->certification->content->salary)) {
			$output["salary"] = $this->certification->content->salary;
        } else {
			$output["salary"] = 0;
        }

        if (isset($this->certification->content->pdf_file)) {
            $output["file"] = $this->certification->content->pdf_file;
        }

        if (isset($this->certification->content->income_prove_image)) {
            $output["income_prove_images"] = $this->certification->content->income_prove_image;
        }

        if (isset($this->certification->content->auxiliary_image)) {
            $output["auxiliary_images"] = $this->certification->content->auxiliary_image;
        }

        if (isset($this->certification->content->license_image)) {
            $output["license_images"] = $this->certification->content->license_image;
        }

        if (isset($this->certification->content->business_image)) {
            $output["business_images"] = $this->certification->content->business_image;
        }

        return $output;
    }

    public function mapMessages($messages)
    {
        $output = [];
        foreach ($messages as $message) {
            $output[] = $this->mapMessage($message);
        }
        return $output;
    }

    public function mapMessage($message)
    {
        $output = [
            "stage" => isset($message->stage) ? $message->stage : '',
            "status" => isset($message->status) ? $message->status : '',
            "message" => is_array($message->message) ? $message->message : [$message->message],
        ];

        if (isset($message->rejected_message)) {
            $output["rejected_message"] = $message->rejected_message;
        }
        return $output;
    }
}
