<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Product_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getProductInfo($productId, $subProductId){
        $product_list = $this->CI->config->item('product_list');
        $sub_product_list = $this->CI->config->item('sub_product_list');

        $product = isset($product_list[$productId]) ? $product_list[$productId] : $product_list[1];
        $sub_product_id = $subProductId;
        if(isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product'])){
            $product = $this->trans_sub_product($product,$sub_product_id);
        }

        return $product;
    }

    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $product['name'].'/'.$sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'status' => $sub_product['status'],
        );
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->CI->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }
}