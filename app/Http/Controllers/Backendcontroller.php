<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Backendcontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request)
    {
        $this->validate($request, [
            'account' => 'required|alpha_dash',
            'password' => 'required|alpha_dash'
        ], [
            'account.required' => '請輸入帳號',
            'password.required' => '請輸入密碼',
            'account.alpha_dash' => '帳號錯誤',
            'password.alpha_dash' => '密碼錯誤',
        ]);

        $input = $request->all();
        if ($input['account'] !== 'zxc' || $input['password'] !== 'zxc') {
            return response()->json(['帳號密碼錯誤'], 400);
        } else {
            Session::put('isLogin', true);
            return response()->json('success', 200, ['isLogin' => Session::get('isLogin')]);
        }
    }
    public function logout(Request $request)
    {
        Session::forget('isLogin');

        return response()->json('sucess');
    }
}
