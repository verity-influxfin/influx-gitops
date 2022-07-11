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
            'certifications_stage' => $sub_product['certifications_stage'] ?? [],
            'option_certifications' => $sub_product['option_certifications'] ?? [],
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

    /**
     * 透過傳入產品結構取得產品所需之徵信項
     * @param $product: 產品結構物件
     * @param array $associate_list: 自然關係人編號列表
     * @return array
     */
    public function get_product_certs_by_product($product, array $associate_list=[]): array
    {
        return $this->_get_product_certs($product, $associate_list);
    }

    /**
     * 透過傳入產品編號取得產品所需之徵信項
     * @param $product_id
     * @param $sub_product_id
     * @param array $associate_list: 自然關係人編號列表
     * @return array
     */
    public function get_product_certs_by_product_id($product_id, $sub_product_id, array $associate_list=[]): array
    {
        $product = $this->getProductInfo($product_id, $sub_product_id);
        return $this->_get_product_certs($product, $associate_list);
    }

    /**
     * 取得產品所需之徵信項
     * @param $product: 產品結構物件
     * @param array $associate_list: 自然關係人編號列表
     * @return array
     */
    private function _get_product_certs($product, $associate_list): array
    {
        $product_certs = $product['certifications'] ?? [];
        if (($product['check_associates_certs'] ?? FALSE))
        {
            $associates_certifications_config = $this->CI->config->item('associates_certifications');
            $associates_certifications = $associates_certifications_config[$product['id']];
            foreach ($associate_list as $associate_char)
            {
                $product_certs = array_merge($product_certs, $associates_certifications[$associate_char]);
            }
            $product_certs = array_unique($product_certs);
        }
        return $product_certs;
    }
}