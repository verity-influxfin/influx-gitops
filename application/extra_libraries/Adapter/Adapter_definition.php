<?php
namespace Adapter;
defined('BASEPATH') OR exit('No direct script access allowed');

interface Adapter_definition
{
    function convert_text(array $data) : array;


}