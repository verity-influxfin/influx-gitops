<?php
/**
 * 確認徵信項是否審核失敗
 * @param $exist_target_submitted : 是否已有送出案件
 * @param $certificate_status : 徵信項是否已送出審核過 (user_certification.certificate_status)
 * @param $status : 徵信項審核狀態 (user_certification.status)
 * @param $expire_time
 * @return bool
 */
function certification_truly_failed($exist_target_submitted, $certificate_status, $status, $expire_time): bool
{
    if ($exist_target_submitted === TRUE || ($exist_target_submitted === FALSE && $certificate_status == 1))
    {
        if ($status == CERTIFICATION_STATUS_FAILED)
        {
            return TRUE;
        }

        if ($expire_time <= time())
        {
            return TRUE;
        }
    }

    return FALSE;
}