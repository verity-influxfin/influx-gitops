<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * utility 類別自動載入器
 *
 * @param    string    $util_name    util 類別名稱
 *
 * @return             util 物件
 * 
 * @created_at                       2021-07-30
 * @created_by                       Jack
 */
if ( ! function_exists('utility'))
{
    function utility(string $util_name, ...$args)
    {
        spl_autoload_register(function($class_name) {
            $filename = APPPATH . sprintf('libraries/utilities/%s.php', $class_name);
            if ( is_readable($filename))
            {
                require_once($filename);
            }
        });

        $util_name = ucfirst(strtolower($util_name));

        if (class_exists($util_name))
        {
            return (new \ReflectionClass($util_name))
                    ->newInstanceArgs($args);
        }
        return FALSE;
    }
}

/**
 * service 類別自動載入器
 *
 * @param    string    $util_name    util 類別名稱
 *
 * @return             util 物件
 * 
 * @created_at                       2021-07-30
 * @created_by                       Jack
 */
if ( ! function_exists('Service'))
{
    function Service(string $name, ...$args)
    {
        $name = ucfirst(strtolower($name)) . '_service';

        if (class_exists($name))
        {
            return (new \ReflectionClass($name))
                    ->newInstanceArgs($args);
        }
        throw new Exception(sprintf('Service %s Not Found.', $name));
    }
}

/**
 * Entity 類別自動載入器
 *
 * @param    string    $name    類別名稱
 *
 * @return             Entity 物件
 * 
 * @created_at                       2021-07-30
 * @created_by                       Jack
 */
if ( ! function_exists('Entity'))
{
    function Entity(string $name, ...$args)
    {
        $name = ucfirst(strtolower($name)) . '_entity';

        if (class_exists($name))
        {
            return (new \ReflectionClass($name))
                    ->newInstanceArgs($args);
        }
        throw new Exception(sprintf('Entity %s Not Found.', $name));
    }
}