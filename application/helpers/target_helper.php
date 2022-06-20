<?php

/**
 * 依照逾期天數判斷有無逾期
 * @param $days: 逾期天數
 * @return bool
 */
function isDelayed($days): bool
{
    return ($days > GRACE_PERIOD);
}

/**
 * 取得已排序的應付交易科目列表 (依照償還合約順序)
 * @return array
 */
function getSortingARSourceList() : array {
    return [SOURCE_AR_LAW_FEE, SOURCE_AR_DAMAGE, SOURCE_AR_INTEREST, SOURCE_AR_DELAYINTEREST, SOURCE_AR_PRINCIPAL];
}

/**
 * 取得回款時需計算平台手續費的應付交易科目
 * @return array
 */
function getPlatformFeeRelatedARSourceList() : array {
    return [SOURCE_AR_INTEREST, SOURCE_AR_DELAYINTEREST, SOURCE_AR_PRINCIPAL];
}
// 應付轉為已付科目 mapping list
function convertARSourceToChargeSource($ARSource) : int {
    switch ($ARSource) {
        case SOURCE_AR_PRINCIPAL:
            return SOURCE_PRINCIPAL;
        case SOURCE_AR_INTEREST:
            return SOURCE_INTEREST;
        case SOURCE_AR_DAMAGE:
            return SOURCE_DAMAGE;
        case SOURCE_AR_DELAYINTEREST:
            return SOURCE_DELAYINTEREST;
        case SOURCE_AR_FEES:
            return SOURCE_FEES;
        case SOURCE_AR_LAW_FEE:
            return SOURCE_LAW_FEE;
        default:
            error_log("Not found the account payable source at convertARSourceToChargeSource");
            return 0;
    }
}

/**
 * 確認是否有待核可案件已一鍵送出
 * @param $user_id
 * @return mixed
 */
function exist_approving_target_submitted($user_id)
{
    $CI =& get_instance();
    $CI->load->model('loan/target_model');
    return $CI->target_model->chk_exist_by_status([
        'user_id' => $user_id,
        'status' => TARGET_WAITING_APPROVE,
        'certificate_status' => [TARGET_CERTIFICATE_SUBMITTED, TARGET_CERTIFICATE_RE_SUBMITTING]
    ]);
}

/**
 * 確認案件的提交狀態是否已提交過一次
 * @param int $target_cert_status : 提交狀態 (target.certificate_status)
 * @return bool
 */
function chk_target_submitted(int $target_cert_status): bool
{
    switch ($target_cert_status)
    {
        case TARGET_CERTIFICATE_SUBMITTED: // 已提交
        case TARGET_CERTIFICATE_RE_SUBMITTING: // 已提交被退回，重新提交中
            return TRUE;
        default:
            return FALSE;
    }
}