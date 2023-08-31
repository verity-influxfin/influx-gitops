<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_definition;

abstract class Credit_base implements Credit_definition
{
    public $content;
    protected $score = 0;
    protected $option = '-';
    protected $CI;
    static public $item = '項目名稱';
    static public $subitem = '項目說明';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        $this->content = $content;
        $this->CI = &get_instance();
    }

    protected function set_score($score, $option = NULL)
    {
        $this->score = $score;
        $this->option = $option;
    }

    /**
     * 取得分數
     * @return int
     */
    public function get_score(): int
    {
        return $this->score;
    }

    /**
     * 取得評分標準
     * @return string
     */
    public function get_option(): string
    {
        return $this->option;
    }

    /**
     * 取得項目說明
     * @return string
     */
    public function get_item(): string
    {
        return static::$item;
    }

    /**
     * 取得詳細說明
     * @return string
     */
    public function get_subitem(): string
    {
        return static::$subitem;
    }
}