<?php

class School_output
{
	protected $school;
	protected $systemMapping;

	public function __construct($params)
	{
		if (!isset($params["data"])) {
			throw new OutOfBoundsException("Data to construct school output is not found");
		}

		$this->school = $this->convertToSchoolObject($params["data"]);
		$this->loadSystemMapping();
	}

	public function toOne()
	{
		if (!$this->school) {
			return [];
		}
		return $this->map($this->school);
	}

	public function map($school, $withSensitiveInfo = false)
	{
		$output = [
			"name" => $school->name,
			"department" => $school->department,
			"major" => $school->major,
			"system" => isset($this->systemMapping[$school->system]) ? $this->systemMapping[$school->system] : '',
		];

		return $output;
	}

	public function convertToSchoolObject($schoolInputs)
	{
		$school = new stdClass();
		foreach ($schoolInputs as $schoolInput) {
			switch ($schoolInput->meta_key) {
				case "school_name":
					$school->name = $schoolInput->meta_value;
					break;
				case "school_department":
					$school->department = $schoolInput->meta_value;
					break;
				case "school_major":
					$school->major = $schoolInput->meta_value;
					break;
				case "school_system":
					$school->system = $schoolInput->meta_value;
					break;
			}
		}
		return $school;
	}

	public function loadSystemMapping()
	{
		$ci =& get_instance();
		$this->systemMapping = $ci->config->item('school_system');
	}
}
