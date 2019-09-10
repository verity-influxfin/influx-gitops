<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Compare_lib{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function contentCheck($compares,$content,$ch=false)
    {
        $checkType = $ch!=false?'/u':'/';
        return preg_match('/'.$compares.$checkType, $content, $re)?$compares=$re[0]:false;
    }

    public function dataExtraction($pattern,$filter,$content,$ch=false)
    {
        $checkType = $ch!=false?'/u':'/';
        $res = preg_match('/'.$pattern.$checkType, $content, $re)?$compares=$re[0]:'';
        $res = preg_replace('/'.$filter.$checkType,'',$res);
        return $res;
    }

    public function dateContentCheck($compares,$content)
    {
        $content = $this->dateFormat($content);
        return preg_match('/'.$compares.'/', $content, $re)?$compares=$re[0]:false;
    }

    private function dateFormat($content)
    {
        return preg_replace_callback(
            '/年|\d{1,2}月|\d{1,2}日/',
            function ($matches) {
                $match = '';
                if(strpos($matches[0],'月')){
                    $match = (preg_match('/\d\d/',$matches[0])!=1?'0':'').preg_replace('/\D/','',$matches[0]);
                }else if(strpos($matches[0],'日')){
                    $match = (preg_match('/\d\d/',$matches[0])!=1?'0':'').preg_replace('/\D/','',$matches[0]);
                }
                else{//
                    $match = preg_replace('/\D/','',$matches[0]);
                }
                return $match;
            },
            $content
        );
    }
}