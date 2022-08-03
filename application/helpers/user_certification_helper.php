<?php
/**
 * 確認徵信項是否審核失敗
 * @param $exist_target_submitted : 是否已有送出案件
 * @param $certification_id : 徵信項id (user_certification.id)
 * @param int $investor : 借款端/投資端
 * @param bool $is_judicial_product
 * @return bool
 */
function certification_truly_failed($exist_target_submitted, $certification_id, int $investor = USER_BORROWER, bool $is_judicial_product = FALSE): bool
{
    $cert = \Certification\Certification_factory::get_instance_by_id($certification_id);
    if (empty($cert))
    {
        return FALSE;
    }

    if ($investor == USER_INVESTOR ||
        $is_judicial_product === TRUE ||
        ($exist_target_submitted === TRUE || ($exist_target_submitted === FALSE && $cert->is_submit_to_review()))
    )
    {
        if ($cert->is_failed())
        {
            return TRUE;
        }

        if ($cert->is_expired())
        {
            return TRUE;
        }
    }

    return FALSE;
}