<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rating_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }
	
	public function School(){
		return true;
	}

	
	public function student_card(){
		return true;
	}
	
}
