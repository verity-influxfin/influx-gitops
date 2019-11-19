<?php

class Joint_credit_output
{
    protected $joint_credits;
    protected $certification;

    public function __construct($params)
    {
        if (!isset($params["data"])) {
            throw new OutOfBoundsException("Data to construct joint credit output is not found");
        }

        $this->joint_credits = $params["data"];
		if (isset($params["certification"])) {
			$this->certification = $params["certification"];
		}
    }

    public function toOne()
    {
        if (!$this->joint_credits) {
            return [];
        }
        return $this->map($this->joint_credits);
    }

    public function map($joint_credits, $withSensitiveInfo = false)
    {
        $output = [
            "status" => $joint_credits->status,
            "messages" => $this->mapMessages($joint_credits->messages),
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
