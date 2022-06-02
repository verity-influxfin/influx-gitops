<?php

/**
 * 確認是否為企金產品
 * @param int $product_id
 * @return bool
 */
function is_judicial_product(int $product_id): bool
{
    if ($product_id < PRODUCT_FOR_JUDICIAL)
    {
        return FALSE;
    }
    return TRUE;
}