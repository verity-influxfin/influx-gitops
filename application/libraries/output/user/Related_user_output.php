<?php

class Related_user_output
{
	protected $records;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct user output is not found");
		}

		$this->records = $params["data"];
	}

	public function toMany($withSensitiveInfo = false)
	{
		if (!$this->records) {
			return [];
		}

		$output = [];
		if (isset($this->records->same_device_id)) {
			foreach ($this->records->same_device_id as $user) {
				$output[] = $this->map("same_device_id", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->same_contact)) {
			foreach ($this->records->same_contact as $user) {
				$output[] = $this->map("same_contact", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->emergency_contact)) {
			foreach ($this->records->emergency_contact as $user) {
				$output[] = $this->map("emergency_contact", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->same_bank_account)) {
			foreach ($this->records->same_bank_account as $user) {
				$output[] = $this->map("same_bank_account", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->same_id_number)) {
			foreach ($this->records->same_id_number as $user) {
				$output[] = $this->map("same_id_number", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->same_phone_number)) {
			foreach ($this->records->same_phone_number as $user) {
				$output[] = $this->map("same_phone_number", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->same_address)) {
			foreach ($this->records->same_address as $user) {
				$output[] = $this->map("same_address", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->introducer) && $this->records->introducer) {
			$output[] = $this->map("introducer", $this->records->introducer, $withSensitiveInfo);
		}
		if (isset($this->records->same_ip)) {
			foreach ($this->records->same_ip as $user) {
				$output[] = $this->map("same_ip", $user, $withSensitiveInfo);
			}
		}
		if (isset($this->records->judicialyuan)) {
		    $output['userName'] = $this->records->judicialyuan['userName'];
		    $output['userBirthday'] = $this->records->judicialyuan['userBirthday'];
			foreach ($this->records->judicialyuan['list'] as $user) {
				$output['judicial_yuan'][] = $this->mapjudicialyuan("judicial_yuan", $user);
			}
		}
		return $output;
	}

	public function map($reason, $user, $withSensitiveInfo = false)
	{
		$output = [
			"reason" => $reason,
			"related_value" => '',
			"investor_status" => $user->investor_status,
			"borrower_status" => $user->status,
			"judicial_yuan" => $user->status,
			"id" => $user->id,
		];

		if ($reason == "same_phone_number") {
			$output["related_value"] = $user->phone;
		}

		if ($reason == "same_ip") {
			$output["related_value"] = $user->login_ip;
		}

		if ($reason == "same_id_number") {
			$output["related_value"] = $user->id_number;
		}

		if ($reason == "introducer") {
			$output["related_value"] = $user->promote_code;
		}

		return $output;
	}

	public function mapjudicialyuan($reason, $list)
	{
		$output = [];

        if ($reason == "judicial_yuan") {
            $output = [
                'name' => $list['name'],
                'count' => $list['count']
            ];
        }

		return $output;
	}
}
