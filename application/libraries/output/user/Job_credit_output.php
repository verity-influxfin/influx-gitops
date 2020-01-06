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
        if (!$this->job_credits) {
            return [];
        }
        return $this->map($this->job_credits);
    }

    public function map($job_credits, $withSensitiveInfo = false)
    {
        $output = [
            "status" => $job_credits->status,
            "messages" => $this->mapMessages($job_credits->messages),
        ];

		if (isset($this->certification->status)) {
			if ($this->certification->status == 1) {
				$output["status"] = "success";
			} elseif ($this->certification->status == 2) {
				$output["status"] = "failure";
			} elseif ($this->certification->status == 3) {
				$output["status"] = "pending";
			}
		}

		if (isset($this->certification->content->pdf_file)) {
			$output["file"] = $this->certification->content->pdf_file;
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
            "stage" => $message->stage,
            "status" => $message->status,
            "message" => is_array($message->message) ? $message->message : [$message->message],
        ];

        if (isset($message->rejected_message)) {
            $output["rejected_message"] = $message->rejected_message;
        }
        return $output;
    }
}
