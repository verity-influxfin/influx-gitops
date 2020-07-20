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
        $input['email'] = strpos($input['email'], '@') ? base64_encode($input['email']) : $input['email'];
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
                alert("電子信箱驗證已過期，請重新註冊(' . $data['error'] . ')");
                console.log("error:' . $errorList[$data['error']] . '");
            </script>
            ';
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'account' => 'required',
            'password' => 'required'
        ], [
            'account.required' => '請輸入帳號',
            'password.required' => '請輸入密碼',
        ]);

        $input = $request->all();

        $userInfo = DB::table('user')->select(['account', 'identity'])->where(
            [
                ['account', '=', $input['account']],
                ['password', '=', sha1($input['password'])]
            ]
        )->first();

        if (!$userInfo) {
            return response()->json(['帳號密碼錯誤'], 400);
        } else {
            Session::put('isLogin', true);
            Session::put('identity', $userInfo->identity);
            return response()->json(['isLogin' => Session::get('isLogin'), 'identity' => Session::get('identity')], 200);
        }
    }

    public function logout(Request $request)
    {
        Session::forget('isLogin');

        return response()->json('sucess');
    }

    public function getKnowledge(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where('type', '=', 'article')->orderBy('post_date', 'desc')->get();

        foreach ($knowledge as $index => $value) {
            if (!$value->category) {
                $knowledge[$index]->category = "";
            }
        }

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

    public function uploadKnowledgeIntroImg(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload/article', "$filename");
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
                $file->move('upload/article', "$filename");
                $pic_path = 'upload/article/' . $filename;
                echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(0, "' . $pic_path . '","");</script>';
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }


    public function uploadVideoIntroImg(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload/video', "$filename");
                return response()->json($filename, 200);
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }

    public function uploadVideoImg(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload/video', "$filename");
                $pic_path = 'upload/video/' . $filename;
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

    public function getPhoneData(Request $request)
    {
        $phone = DB::table('product_phone')->select('*')->get();

        return response()->json($phone, 200);
    }

    public function uploadPhoneFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload/phone', "$filename");
                return response()->json($filename, 200);
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }

    public function modifyPhoneData(Request $request)
    {

        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                if ($this->inputs['actionType'] === 'insert') {
                    DB::table('product_phone')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('product_phone')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deletePhoneData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('product_phone')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getMilestoneData(Request $request)
    {
        $milestone = DB::table('milestone')->select('*')->orderBy('hook_date', 'desc')->get();

        return response()->json($milestone, 200);
    }

    public function modifyMilestoneData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                if ($this->inputs['actionType'] === 'insert') {
                    DB::table('milestone')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('milestone')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deleteMilestoneData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('milestone')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getRotationData(Request $request)
    {
        $data = DB::table('prize')->select('*')->get();

        return response()->json($data, 200);
    }

    public function ratate(Request $request)
    {
        $inputs = $request->all();
        $key = $this->_lottery($inputs['id']);
        return response()->json($key, 200);
    }

    private function _lottery($id)
    {
        $data = DB::table('prize')->select('*')->get();
        $max = 0;
        $gap = [];

        foreach ($data as $row) {
            $max += $row->weights * $row->probability;
            $gap[] = $row->weights * $row->probability;
        }

        $key = '';
        foreach ($gap as $k => $proCur) {
            $randNum = mt_rand(1, $max);
            if ($randNum <= $proCur) {
                $key =  $k;
                break;
            } else {
                $max -= $proCur;
            }
        }
        if ($data[$key]->amount > 0) {
            DB::table('prize')->where('ID', $data[$key]->ID)->update(['amount' => $data[$key]->amount - 1]);
            DB::table('list')->insert(['prize_id' => $data[$key]->ID, 'user_id' => $id, 'date_time' => date('Y-m-d H:i:s')]);

            return $key;
        } else {
            $this->_lottery($id);
        }
    }

    public function checkStatus(Request $request)
    {
        $userData = Session::get('userData');

        $row = DB::table('list')->select('*')->where('user_id', '=', $userData['id'])->get();

        if (!$userData['name']) {
            if (count($row) == 0) {
                return response()->json(['status' => false, 'message' => ''], 200);
            } else {
                return response()->json(['status' => true, 'message' => '完成實名可以再一次抽獎機會！'], 200);
            }
        } else {
            if (count($row) < 2) {
                return response()->json(['status' => false, 'message' => ''], 200);
            } else {
                return response()->json(['status' => true, 'message' => '您已抽過獎囉！'], 200);
            }
        }
    }

    public function recaptcha(Request $request)
    {
        $inputs = $request->all();

        $curlScrapedPage = shell_exec('curl -X GET "https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode('6LfQla4ZAAAAAHTDVvzN1hnlNQji_CwaR8KArj1v') . '&response=' . urlencode($inputs['token']) . '"');
        $responseKeys = json_decode($curlScrapedPage, true);

        return response()->json('', $responseKeys['score'] >= 0.5 ? 200 : 400);
    }

    public function getMediaData(Request $request)
    {
        $mediaData = DB::table('media')->select('*')->get();

        return response()->json($mediaData, 200);
    }

    public function modifyMediaData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                if ($this->inputs['actionType'] === 'insert') {
                    DB::table('media')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('media')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deleteMediaData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('media')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getPartnerData(Request $request)
    {
        $partnerData = DB::table('partner')->select('*')->get();

        return response()->json($partnerData, 200);
    }

    public function modifyPartnerData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                if ($this->inputs['actionType'] === 'insert') {
                    DB::table('partner')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('partner')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deletePartnerData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('partner')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function uploadPartnerImg(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filename = $file->getClientOriginalName();
                $file->move('upload/partner', "$filename");
                return response()->json($filename, 200);
            }
        } else {
            echo '<script type="text/javascript">alert("上傳失敗");</script>';
        }
    }
    
    public function getFeedbackData(Request $request)
    {
        $partnerData = DB::table('feedback')->select('*')->get();

        return response()->json($partnerData, 200);
    }

    public function modifyFeedbackData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                if ($this->inputs['actionType'] === 'insert') {
                    DB::table('feedback')->insert($this->inputs['data']);
                } else if ($this->inputs['actionType'] === 'update') {
                    DB::table('feedback')->where('ID', $this->inputs['ID'])->update($this->inputs['data']);
                }
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function deleteFeedbackData(Request $request)
    {
        $this->inputs = $request->all();

        try {
            $exception = DB::transaction(function () {
                DB::table('feedback')->where('ID', '=', $this->inputs['ID'])->delete();
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
}
