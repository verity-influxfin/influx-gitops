<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Greetingcontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
    }

    public function uploadGreetingAuthorImg(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $mimeType = $file->getClientOriginalExtension();
            $arrowType =  config('verifyfiletype')['image'];

            if (!in_array(strtolower($mimeType), $arrowType)) {
                return response()->json('上傳失敗，請確認檔案為圖片', 400);
            }


            if ($file->isValid()) {
                $filename =  date('YmdHis') . "_" . $file->getClientOriginalName();
                $file->move('upload/greeting', $filename);
                return response()->json($filename, 200);
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }
}
