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
     * 取得主產品設定
     * @return array
     */
    public function get_product_list(): array
    {
        return $this->CI->config->item('product_list');
    }

    /**
     * 取得主產品詳細資訊
     * @param int $product_id
     * @return array
     */
    public function get_product_info(int $product_id): array
    {
        $product_list = $this->get_product_list();
        return $product_list[$product_id] ?? [];
    }

    /**
     * 取得子產品設定
     * @return mixed
     */
    public function get_sub_product_list()
    {
        return $this->CI->config->item('sub_product_list');
    }

    /**
     * 檢查是否為子產品
     * @param $product : 主產品設定
     * @param $sub_product_id : 子產品ID
     * @return bool
     */
    public function is_sub_product($product, $sub_product_id): bool
    {
        $sub_product_list = $this->get_sub_product_list();
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']);
    }

    public function get_exact_product(int $product_id, int $sub_product_id): array
    {
        // 取得主產品資訊
        $product = $this->get_product_info($product_id);

        // 若為子產品，則改取子產品的資訊
        if ($this->is_sub_product($product, $sub_product_id))
        {
            $product = $this->trans_sub_product($product, $sub_product_id);
        }

        return $product;
    }

    /**
     * 檢查該產品是否有申貸年齡限制
     * @param int $product_id : 主產品ID
     * @param int $sub_product_id : 子產品ID
     * @return bool
     */
    public function need_chk_allow_age(int $product_id, int $sub_product_id = 0): bool
    {
        $product = $this->get_exact_product($product_id, $sub_product_id);
        $company = $this->get_no_allow_age_product_list();
        if (in_array($product['visul_id'], $company))
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 取得無需檢查申貸年齡的產品 visul_id
     * @return string[]
     */
    private function get_no_allow_age_product_list(): array
    {
        return [
            'DS2P1', // 在庫車融資
        ];
    }

    /**
     * 檢查年齡是否符合限制
     * @param int $age : 年齡
     * @param int $product_id : 主產品ID
     * @param int $sub_product_id : 子產品ID
     * @return bool
     */
    public function is_age_available(int $age, int $product_id, int $sub_product_id = 0): bool
    {
        $product = $this->get_exact_product($product_id, $sub_product_id);
        if ($age < ($product['allow_age_range'][0] ?? 20) || $age > ($product['allow_age_range'][1] ?? 55))
        {
            return FALSE;
        }
        return TRUE;
    }
}