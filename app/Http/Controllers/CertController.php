<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertController extends Controller
{
    public function chk_status($cert_alias): JsonResponse
    {
        return response()->json(['success' => true]);
    }
}
