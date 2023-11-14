<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

interface Credit_definition
{
    function scoring(): Credit_definition;

    function get_score(): int;

    function get_item(): string;

    function get_subitem(): string;

    function get_option(): string;
}