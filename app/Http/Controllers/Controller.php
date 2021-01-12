<?php

namespace App\Http\Controllers;

use App\Rules\mobile;
use App\Rules\identity;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiGetway;

    public function __construct()
    {
        $this->apiGetway = config('api.apiGetway');
    }

    public function getListData(Request $request)
    {
        $data = json_decode(file_get_contents('data/listData.json'), true);

        return response()->json($data, 200);
    }

    public function getIndexBanner(Request $request)
    {
        $banner = DB::table('banner')->select(['desktop', 'mobile'])->where([['isActive', '=', 'on'], ['type', '=', 'index']])->orderBy('post_modified', 'desc')->get();

        return response()->json($banner, 200);
    }

    public function getKnowledgeData(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where([['type', '=', 'article'], ['status', '=', 'publish']])->orderBy('post_date', 'desc')->get();

        return response()->json($knowledge, 200);
    }

    public function getVideoData(Request $request)
    {
        $input = $request->all();

        $result = [];
        if ($input['filter'] !== 'share') {
            $result = DB::table('interview')->select('*')->where('category', '=', $input['filter'])->orderBy('post_date', 'desc')->get();
        } else {
            $result = DB::table('knowledge_article')->select('*')->where([['type', '=', 'video'], ['status', '=', 'publish']])->orderBy('post_modified', 'desc')->get();
        }

        return response()->json($result, 200);
    }

    public function getNewsData(Request $request)
    {
        $news = DB::table('news')->select('*')->orderBy('post_date', 'desc')->get();

        return response()->json($news, 200);
    }

    public function getNewsArticle(Request $request)
    {
        $input = $request->all();

        $news = DB::table('news')->select('*')->where('ID', '=', $input['ID'])->first();

        return response()->json($news, 200);
    }

    public function getInvestTonicData(Request $request)
    {
        $result = DB::table('knowledge_article')->select('*')->where('category', '=', 'investtonic')->orderBy('post_date', 'desc')->get();

        return response()->json($result, 200);
    }

    public function getArticleData(Request $request)
    {
        $input = $request->all();

        @list($type, $params) = explode('-', $input['filter']);
        $result = DB::table('knowledge_article')->select('*')->where('ID', '=', $params)->orderBy('post_date', 'desc')->first();

        return response()->json($result, 200);
    }

    public function getVideoPage(Request $request)
    {
        $input = $request->all();

        $knowledge = DB::table('knowledge_article')->select('*')->where('ID', '=', $input['filter'])->orderBy('post_date', 'desc')->first();

        return response()->json($knowledge, 200);
    }

    public function getExperiencesData(Request $request)
    {
        $input = $request->all();

        $filter = [['isActive', '=', 'on'], ['isRead', '=', '1']];

        if ($input['type']) {
            $filter[] = ['category', '=', $input['type']];
        }

        $experiences = DB::table('interview')->select(['feedback', 'imageSrc', 'video_link', 'post_title', 'rank', 'type'])->where($filter)->get();
        return response()->json($experiences, 200);
    }

    public function getServiceData(Request $request)
    {
        $data = json_decode(file_get_contents('data/serviceData.json'), true);

        return response()->json($data, 200);
    }

    public function getQaData(Request $request)
    {
        $input = $request->all();

        $data = json_decode(file_get_contents('data/qaData.json'), true);

        return response()->json($data[$input['filter']], 200);
    }

    public function getMobileData(Request $request)
    {

        $curlScrapedMobilePage = shell_exec('curl -X GET "https://coop.influxfin.com/api/product/list?type=0"');
        $mobileData = json_decode($curlScrapedMobilePage, true);

        return response()->json($mobileData, 200);
    }

    public function getMilestoneData(Request $request)
    {
        $milestone = DB::table('milestone')->select('*')->orderBy('hook_date', 'desc')->get();

        return response()->json($milestone, 200);
    }

    public function getMediaData(Request $request)
    {
        $media = DB::table('media')->select('*')->orderBy('date', 'desc')->get();

        return response()->json($media, 200);
    }

    public function getPartnerData(Request $request)
    {
        $partner = DB::table('partner')->select('*')->orderBy('order')->get();

        return response()->json($partner, 200);
    }

    public function getBannerData(Request $request)
    {
        $input = $request->all();

        $bannerData = json_decode(file_get_contents('data/bannerData.json'), true);

        $banner = DB::table('banner')->select(['desktop', 'mobile'])->where([['isActive', '=', 'on'], ['type', '=', $input['filter']]])->orderBy('post_modified', 'desc')->get();

        $data[] = $bannerData[$input['filter']];

        foreach ($banner as $index => $row) {
            $data[$index + 1]['desktop'] = $row->desktop;
            $data[$index + 1]['mobile'] = $row->mobile;
        }

        return response()->json($data, 200);
    }

    public function getApplydata(Request $request)
    {
        $input = $request->all();

        $data = json_decode(file_get_contents('data/applyData.json'), true);

        return response()->json($data[$input['filter']], 200);
    }

    public function action(Request $request)
    {
        $this->inputs = $request->all();

        $this->inputs['datetime'] = date('Y-m-d H:i:s');

        $this->validate($request, [
            'email' => 'email',
            'phone' => 'digits:10',
        ], [
            'email.email' => '信箱格式錯誤',
            'phone.digits' => '電話格式錯誤'
        ]);

        try {
            $exception = DB::transaction(function () {
                DB::table('cooperation')->insert($this->inputs);
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function sendFeedback(Request $request)
    {
        $this->inputs = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'message' => 'max:100',
            'rank' => 'in:officeWorker,student',
            'type' => 'in:invest,loan'
        ], [
            'name.required' => '請輸入姓名',
            'message.max' => '字數太長，請縮短字數',
            'type.in' => '請選擇身分',
            'rank.in' => '請選擇使用類別',
        ]);

        $userData = Session::get('userData');

        $type = config('feedback');

        $this->inputs['date'] = date('Y-m-d H:i:s');
        $this->inputs['imageSrc'] = 'images/' . $type[$userData['sex']][$this->inputs['rank']];

        try {
            $exception = DB::transaction(function () {
                DB::table('feedback')->insert($this->inputs);
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function campusUploadFile(Request $request)
    {
        $this->inputs = $request->all();

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $mimeType = $file->getClientOriginalExtension();
            $arrowType =  config('verifyfiletype')[$this->inputs['fileType']];

            if (!in_array(strtolower($mimeType), $arrowType)) {
                return response()->json('上傳失敗，請確認檔案格式', 400);
            }

            if ($file->isValid()) {
                $newFile =  date('YmdHis') . "_" . $file->getClientOriginalName();
                $file->move('upload/campus/' . $this->inputs['fileType'], $newFile);
                return response()->json($newFile, 200);
            }
        } else {
            return response()->json('上傳失敗，請洽工程師', 400);
        }
    }

    public function campusSignup(Request $request)
    {
        $this->inputs = $request->all();

        $this->validate($request, [
            'teamName' => 'required',
        ]);

        foreach ($this->inputs['memberList'] as $key => $value) {
            $memberValidator[$key]  = Validator::make($value, [
                'name' => 'required',
                'mobile' => ['required', new mobile()],
                'email' => 'required|email',
                'school' => 'required',
                'selfIntro' => 'required',
                'department' => 'required',
                'grade' => 'required',
                'resume' => 'required',
            ]);

            if ($memberValidator[$key]->fails()) {
                return response()->json(['index' => $key, 'errors' => $memberValidator[$key]->errors()], 400);
            }
        }

        try {
            $exception = DB::transaction(function () {
                $id =  DB::table('campusTeam')->insertGetId(['teamName' => $this->inputs['teamName']]);

                foreach ($this->inputs['memberList'] as $key => $value) {
                    $this->inputs['memberList'][$key]['teamID'] = $id;
                }

                DB::table('campusMember')->insert($this->inputs['memberList']);
            }, 5);
            if (is_null($exception)) {
                foreach ($this->inputs['memberList'] as $memberData) {
                    try {
                        app('App\Http\Controllers\SendEmailController')->sendNoticeMail($memberData);
                    } catch (Exception $e) {
                        return response()->json('m', 400);
                    }
                }
                return response()->json('', 200);
            } else {
                return response()->json($exception,  400);
            }
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function routedecode(Request $request)
    {
        $inputs = $request->all();

        if (array_key_exists('routeData', $inputs)) {
            $data = [];
            foreach ($inputs['routeData'] as $row) {
                $data[] = ['street_name' => html_entity_decode($row['street_name'])];
            }

            return response()->json($data, 200);
        }

        return response()->json('', 400);
    }
}
