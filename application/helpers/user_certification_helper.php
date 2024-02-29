<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;

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

/**
 * Check if the PDF got revised.
 * @param $pdf_url
 * @return array [certification status, detail info]
 */
function verify_fraud_pdf($pdf_url): array
{
    $api_url = 'http://' . getenv('PDF_FRAUD_DETECT_IP') . ':' . getenv('PDF_FRAUD_DETECT_PORT');
    $cert_status = CERTIFICATION_STATUS_PENDING_TO_VALIDATE;
    $details = [];
    try
    {
        $request = (new Client(['base_uri' => $api_url]))
            ->request('POST', "/pdf_identifier/pdf_edited_identifier", [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode(['pdf_url' => $pdf_url])
            ]);
        $response_data = json_decode($request->getBody()->getContents(), TRUE);
        if ($response_data['is_producer_valid'])
        {
            $delta_sec = $response_data['mod_delta_sec'];
            if ($delta_sec > 10)
            {
                $cert_status = CERTIFICATION_STATUS_FAILED;
                $details = [
                    '建立時間' => $response_data['creation_datetime'],
                    '修改時間' => $response_data['mod_datetime'],
                    '修改與建立時間差(秒)' => $response_data['mod_delta_sec'],
                    'desc' => 'pdf修改與建立時間差大於10秒'
                ];
            }
            elseif ($delta_sec >= 3) // $delta_sec < 3: pass, not fraud PDF
            {
                $cert_status = CERTIFICATION_STATUS_PENDING_TO_REVIEW;
                $details = [
                    '建立時間' => $response_data['creation_datetime'],
                    '修改時間' => $response_data['mod_datetime'],
                    '修改與建立時間差(秒)' => $response_data['mod_delta_sec'],
                    'desc' => 'pdf修改與建立時間差介於3~10秒間'
                ];
            }
        }
        else
        {
            $cert_status = CERTIFICATION_STATUS_FAILED;
            $details = [
                'desc' => 'pdf用別的軟體編輯過'
            ];
        }
    }
    catch (BadResponseException $e)
    {
        $err_msg = "PDF fraud detect failed: PDF url = {$pdf_url}, response status code = {$e->getResponse()->getStatusCode()}, ";
        $err_msg = $err_msg . "response content = {$e->getResponse()->getBody()->getContents()}";
        log_message('error', $err_msg);
    }
    catch (GuzzleException $e)
    {
        $err_msg = "PDF fraud detect failed: PDF url = {$pdf_url}, error code = {$e->getCode()}, error message = {$e->getMessage()}";
        log_message('error', $err_msg);
    }
    catch (Exception $e)
    {
        $err_msg = "PDF fraud detect failed: PDF url = {$pdf_url}, error code = {$e->getCode()}, error message = {$e->getMessage()}";
        log_message('error', $err_msg);
    }
    return [$cert_status, $details];
}

function get_domicile($address)
{
    $domicile = '';
    preg_match('/([\x{4e00}-\x{9fa5}]+)(縣|市)/u', str_replace('台', '臺', $address), $matches);
    if ( ! empty($matches))
    {
        $domicile = $matches[1];
    }
    return $domicile;
}

/**
 * 確認是否為法人徵信項
 * @param $certification_id
 * @return bool
 */
function is_judicial_certification(int $certification_id): bool
{
    if ($certification_id < CERTIFICATION_FOR_JUDICIAL)
    {
        return FALSE;
    }
    return TRUE;
}