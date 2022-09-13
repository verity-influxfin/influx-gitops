<?php

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

function is_judicial_certification(int $certification_id): bool
{
    if ($certification_id < CERTIFICATION_FOR_JUDICIAL)
    {
        return FALSE;
    }
    return TRUE;
}