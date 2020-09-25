<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

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

    public function getKnowledgeData(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where([['type', '=', 'article'], ['status', '=', 'publish']])->orderBy('post_modified', 'desc')->get();

        return response()->json($knowledge, 200);
    }

    public function getVideoData(Request $request)
    {
        $input = $request->all();

        $result = [];
        if ($input['filter'] !== 'share') {
            $result = DB::table('interview')->select('*')->where('category', '=', $input['filter'])->orderBy('post_modified', 'desc')->get();
        } else {
            $result = DB::table('knowledge_article')->select('*')->where([['type', '=', 'video'], ['status', '=', 'publish']])->orderBy('post_modified', 'desc')->get();
        }

        return response()->json($result, 200);
    }

    public function getNewsData(Request $request)
    {

        $curlScrapedNewsPage = shell_exec("curl -X GET " . $this->apiGetway . "article/news");
        $newsdata = json_decode($curlScrapedNewsPage, true);

        $curlScrapedEventPage = shell_exec("curl -X GET " . $this->apiGetway . "article/event");
        $eventdata = json_decode($curlScrapedEventPage, true);

        $result = array();

        foreach ($newsdata['data']['list'] as $index => $value) {
            $value['updated_at'] = date('Y-m-d', $value['updated_at']);
            $result[] = $value;
        }

        foreach ($eventdata['data']['list'] as $index => $value) {
            $value['updated_at'] = date('Y-m-d', $value['updated_at']);
            $result[] = $value;
        }

        $num = count($result);

        for ($i = 0; $i < $num; $i++) {
            for ($j = $num - 1; $j > $i; $j--) {
                if ($result[$j]['updated_at'] > $result[$j - 1]['updated_at']) {
                    list($result[$j], $result[$j - 1]) = array($result[$j - 1], $result[$j]);
                }
            }
        }

        return response()->json($result, 200);
    }

    public function getInvestTonicData(Request $request)
    {
        $result = DB::table('knowledge_article')->select('*')->where('category', '=', 'investtonic')->orderBy('post_modified', 'desc')->get();

        return response()->json($result, 200);
    }

    public function getArticleData(Request $request)
    {
        $input = $request->all();

        @list($type, $params) = explode('-', $input['filter']);
        $result = DB::table('knowledge_article')->select('*')->where('ID', '=', $params)->orderBy('post_modified', 'desc')->first();

        return response()->json($result, 200);
    }

    public function getVideoPage(Request $request)
    {
        $input = $request->all();

        $knowledge = DB::table('knowledge_article')->select('*')->where('ID', '=', $input['filter'])->orderBy('post_modified', 'desc')->first();

        return response()->json($knowledge, 200);
    }

    public function getExperiencesData(Request $request)
    {
        $input = $request->all();

        $filter = [['isActive', '=', 'on'], ['isRead', '=', '1']];

        if($input['type']){
            $filter[] = ['type','=',$input['type']];
        }

        $knowledge = DB::table('feedback')->select(['feedback', 'imageSrc', 'name', 'rank','type'])->where($filter)->orderBy('date', 'desc')->get();

        return response()->json($knowledge, 200);
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
        $partner = DB::table('partner')->select('*')->get();

        return response()->json($partner, 200);
    }

    public function getBannerData(Request $request)
    {
        $input = $request->all();

        $data = json_decode(file_get_contents('data/bannerData.json'), true);

        return response()->json($data[$input['filter']], 200);
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
        $this->inputs['datetime'] = date('Y-m-d H:i:s');

        $this->validate($request, [
            'name' => 'required',
            'message' => 'max:100',
            'type' => 'in:officeWorker,student'
        ], [
            'name.required' => '請輸入姓名',
            'message.max' => '字數太長，請縮短字數',
            'type.in' => '請選擇身分'
        ]);

        try {
            $exception = DB::transaction(function () {
                DB::table('feedback')->insert($this->inputs);
            }, 5);
            return response()->json($exception, is_null($exception) ? 200 : 400);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getBannerPic(Request $request)
    {

        return [
            [
                "img" => 'images/index-banner.jpg'
            ],
            [
                "img" => 'images/index-banner.jpg'
            ]
        ];
    }
}
