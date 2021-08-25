<?php
function autoload()
{
    set_include_path(APPPATH.'extra_libraries');
    spl_autoload_extensions(".php");
    spl_autoload_register();
    spl_autoload_register(function($class) {
        $class = str_replace('\\', '/', $class);
        require_once $class.EXT;
    }, false);
}
