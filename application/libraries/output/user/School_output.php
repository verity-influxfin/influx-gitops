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
			"name" => isset($school->name)?$school->name:'',
            "department" => isset($school->department)?$school->department:'',
            "major" => isset($school->major)?$school->major:'',
            "system" => isset($school->system) ? $this->systemMapping[$school->system] : '',
		];

		if (isset($school->graduate_date)) {
			$output["graduate_at"] = $school->graduate_date;
		}

		return $output;
	}

	public function convertToSchoolObject($userMeta)
	{
		$school = new stdClass();
		if (isset($userMeta->school_name))
			$school->name = $userMeta->school_name;
		if (isset($userMeta->school_department))
			$school->department = $userMeta->school_department;
		if (isset($userMeta->school_major))
			$school->major = $userMeta->school_major;
		if (isset($userMeta->school_system))
			$school->system = $userMeta->school_system;

		return $school;
	}

	public function loadSystemMapping()
	{
		$ci =& get_instance();
		$this->systemMapping = $ci->config->item('school_system');
	}
}
