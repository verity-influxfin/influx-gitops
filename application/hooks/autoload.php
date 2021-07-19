<?php
function autoload()
{
	spl_autoload_register(function($class) {
		if(file_exists(APPPATH.'extra_libraries/'.$class.EXT)) {
			require_once APPPATH . 'extra_libraries/' . $class . EXT;
		}
	});
}
