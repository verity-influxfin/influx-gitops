<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Cardgamecontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
    }

    public function setGreetingData(Request $request)
    {
        $this->inputs = $request->all();
        $this->data = '';

        try {
            $exception = DB::transaction(function () {
                $id = DB::table('greeting')->insertGetId($this->inputs);
                $this->data = ['token' => base64_encode($id)];
            }, 5);
            return response()->json($this->data, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getGreetingData(Request $request)
    {
        $input = $request->all();

        $id = base64_decode($input['token']);
        $result = DB::table('greeting')->select('*')->where('ID', '=', $id)->first();

        return response()->json($result, 200);
    }

    public function getAns(Request $request)
    {
        $input = $request->all();

        $result = [
            'ans' => $input['qnum'] == 7 && $input['qans'] == 'A' || $input['qnum'] != 7 && $input['qans'] == 'B' ? 0 : 1
        ];
        return response()->json($result, 200);
    }
}
