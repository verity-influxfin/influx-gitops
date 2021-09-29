<?php

namespace RequestValidator;

class ValidatorBase
{
    protected static $instance;

    function __construct()
    {

    }

    public static function getInstance()
    {
        // Check is $_instance has been set
        if(!isset(self::$instance))
        {
            // Creates sets object to instance
            $className = get_called_class();
            self::$instance = new $className();
        }

        // Returns the instance
        return self::$instance;
    }

    /**
     * 驗證是否有權限
     * @param array $input: 輸入的參數
     * @param array $allowedParameters: 要驗證的參數
     * @return bool
     */
    public function checkPermission(array $input, array $allowedParameters): bool
    {
        $permissionDenied = false;
        foreach ($allowedParameters as $key => $v) {
            if(!array_key_exists($key, $input) ||
                ((is_array($v) && !in_array($input[$key], $v)) ||
                 ((is_numeric($v)||is_string($v)) && $input[$key] != $v))
            ) {
                if(!(is_string($v) && $v == "*" && !empty(trim($input[$key] ?? ''))))
                    $permissionDenied = true;
                break;
            }
        }
        return !$permissionDenied;
    }
}