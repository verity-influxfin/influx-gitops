<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Backendcontroller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function verifyemail(Request $request)
    {
        $input = $request->all();
        $input['email'] = strpos($input['email'],'@') ? base64_encode($input['email']) : $input['email'];
        $params = http_build_query($input);

        $curlScrapedPage = shell_exec('curl -X POST "https://stage-api.influxfin.com/api/v2/certification/verifyemail" -d "' . $params . '"');
        $data = json_decode($curlScrapedPage, true);

        if ($data['result'] === 'SUCCESS') {
            echo '
            <script type="text/javascript">
                alert("註冊成功");
                location.replace("https://event.influxfin.com/R/url?p=webbanner");
            </script>
            ';
        } else {
            $errorList = [
                '200' => '參數錯誤',
                '204' => 'Email格式錯誤',
                '303' => '驗證碼錯誤'
            ];

            echo '
            <script type="text/javascript">
                alert("電子信箱驗證已過期，請重新註冊('.$data['error'].')");
                console.log("error:'.$errorList[$data['error']].'");
            </script>
            ';
        }
    }

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

    public function getKnowledge(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where('type', '=', 'article')->orderBy('post_modified', 'desc')->get();

        return response()->json($knowledge, 200);
    }

    public function modifyKnowledge(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                $this->inputs['data']['post_author'] = '1';
                $this->inputs['data']['post_modified'] = date('Y-m-d H:i:s');
                if ($this->inputs['actionType'] === 'insert') {
                    $this->inputs['data']['post_date'] = date('Y-m-d H:i:s');
                    DB::table('knowledge_article')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('knowledge_article')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deleteKonwledge(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('knowledge_article')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload', "$filename");
                return response()->json($filename, 200);
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }
    public function uploadKnowledgeImg(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload', "$filename");
                $pic_path = 'upload/' . $filename;
                echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(0, "' . $pic_path . '","");</script>';
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }
    public function getknowledgeVideoData(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where('type', '=', 'video')->orderBy('post_modified', 'desc')->get();

        return response()->json($knowledge, 200);
    }
}
