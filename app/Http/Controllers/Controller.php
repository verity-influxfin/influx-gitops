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
        $banner = DB::table('banner')->select(['desktop', 'mobile', 'link'])->where([['isActive', '=', 'on'], ['type', '=', 'index']])->orderBy('post_modified', 'desc')->get();

        return response()->json($banner, 200);
    }

    public function getCount()
    {
        $count = DB::table('count')->select(['transactionCount','memberCount','totalLoanAmount'])->latest('id')->get()->first();
        if(empty($count)){
            $count = [
                'transactionCount' => 123,
                'memberCount' => 321,
                'totalLoanAmount' => 1234567
            ];
        }
        return response()->json($count, 200);
    }

    public function sendQuestion(Request $request){
        $input = $request->all();
        if(isset($input['identity']) && isset($input['name']) && isset($input['mail']) && isset($input['phone']) && isset($input['content'])){

            $data = [
                'identity' => $input['identity'],
                'name' => $input['name'],
                'mail' => $input['mail'],
                'phone' => $input['phone'],
                'content' => $input['content'],
                'origin_ip' => $_SERVER['REMOTE_ADDR'],
                'created_at' => date('Y-m-d H:i:s', strtotime('+8 hours')),
            ];
            try {
                DB::table('send_question')->insert($data);
            } catch (Exception $e) {
                return response()->json(['response' => 'error', 'message'=>$e], 501);
            }

            return response()->json(['response' => 'success', 'message' => ''], 200);
        }else{
            return response()->json(['response' => 'error','message' => 'parameter not found'], 501);
        }
    }

    // to do : 計算邏輯待拆離
    public function getBorrowReport(Request $request)
    {
        $input = $request->all();
        $result = [];
        $report = [
            0 => [
                'amount' => 0,
                'rate' => 0,
                'period_range' => 0,
            ],
            1 => [
                'amount' => 80000,
                'rate' => '14~16',
                'period_range' => 24,
            ],
            2 => [
                'amount' => 120000,
                'rate' => '10~13',
                'period_range' => 24,
            ],
            3 => [
                'amount' => 160000,
                'rate' => '8~10',
                'period_range' => 24,
            ],
            4 => [
                'amount' => 200000,
                'rate' => '7~8',
                'period_range' => 24,
            ],
            5 => [
                'amount' => 300000,
                'rate' => '6~7',
                'period_range' => 24,
            ]
        ];
        $total_point = 0;

        if(isset($input['identity']) && !empty($input ['identity'])){
            // 學生
            if($input ['identity'] == 1){
                // 系排名
                if($input['rank']>=30){
                    $total_point += 0;
                }elseif ($input['rank']>=10) {
                    $total_point += 50;
                }else {
                    $total_point += 100;
                }
                // 是否拿過獎學金
                if($input['is_get_prize'] == true){
                    $total_point += 100;
                }else{
                    $total_point += 0;
                }
            }
            // 上班族
            if($input ['identity'] == 2){
                // 教育程度
                switch ($input['educational_level']) {
                    case '學士以下':
                        $total_point += 0;
                        break;
                    case '學士':
                        $total_point += 10;
                        break;
                    case '碩士':
                        $total_point += 15;
                        break;
                    case '博士':
                        $total_point += 20;
                        break;
                    default:
                        $total_point += 0;
                        break;
                }
                // 職業
                $total_point += 10;
                // 是否為上市櫃、金融機構或公家機關
                if($input['is_top_enterprises'] == true){
                    $total_point += 15;
                }else{
                    $total_point += 5;
                }
                // 投保薪資
                if($input['insurance_salary']>=35000){
                    $total_point += 15;
                }elseif ($input['insurance_salary']<35000 && $input['insurance_salary']>=23800) {
                    $total_point += 10;
                }else{
                    $total_point += 0;
                }
                // 貸款餘額
                if($input['debt_amount']>=4){
                    $total_point += 0;
                }else{
                    $total_point += 5;
                }
                // 每月攤還金額
                if($input['monthly_repayment']>=4){
                    $total_point += 5;
                }else{
                    $total_point += 10;
                }
                // 信用卡額度
                $total_point += 10;
                // 信用卡帳單總金額
                if($input['creditcard_bill']>7){
                    $total_point += 5;
                }else{
                    $total_point += 15;
                }
            }

            if($total_point >= 90){
                $result = $report[5];
            }elseif ($total_point >= 80) {
                $result = $report[4];
            }elseif ($total_point >= 50) {
                $result = $report[3];
            }elseif ($total_point >= 70) {
                $result = $report[2];
            }elseif ($total_point >= 20) {
                $result = $report[1];
            }else {
                $result = $report[0];
            }
        }

        try {
            $input['amount'] = $result['amount'];
            $input['rate'] = $result['rate'];
            $input['period_range'] = $result['period_range'];
            DB::table('borrow_report')->insert($input);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }

        return response()->json($result, 200);
    }

    public function getgetCase(Request $request){

        $input = $request->all();

        if(isset($input['status']) && $input['status'] == 10){
            $input['limit'] = 100;
        }
        $params = http_build_query($input);
        // url 待改，主站 api 待開發
        $case_data = shell_exec('curl -X POST "' . $this->apiGetway . 'user/login" -d "' . $params . '"');

        if ($data['result'] === "SUCCESS") {
            $result = $case_data['data']['list'];
        } else {
            return response()->json($data, 400);
        }

        return response()->json($result, 200);
    }

    public function getKnowledgeData(Request $request)
    {
        $knowledge = DB::table('knowledge_article')->select('*')->where([['type', '=', 'article'], ['status', '=', 'publish']])->orderBy('order', 'desc')->orderBy('post_date', 'desc')->get();

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
        $news = DB::table('news')->select('*')->where('status', '=', 'on')->orderBy('post_date', 'desc')->get();

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

        if (isset($input['category']) && !empty($input['category'])) {
            $filter[] = ['category', '=', $input['category']];
        }

        $experiences = DB::table('interview')->select(['ID', 'feedback', 'imageSrc', 'video_link', 'post_title', 'rank', 'type','amount','rate','period_range','spend_day'])->where($filter)->get();
        if(empty($experiences)){
            $experiences = [
                0 => [
                    'ID' => 1,
                    'feedback' => '普匯跟其他保本型的商品比起來，投報率真的相對較高，也比較不需要花太多時間去關注，就可以獲得被動收入。',
                    'imageSrc' => null,
                    'video_link' => 'https://www.youtube.com/embed/oHg5SJYRHA0',
                    'post_title' => '台北投資顧問 葉先生',
                    'rank' => 'officeWorker',
                    'type' => '【投資人專訪】',
                    'amount' => 0,
                    'rate' => 0,
                    'period_range' => 0,
                    'spend_day' => 0
                ],
                1 => [
                    'ID' => 2,
                    'feedback' => '在普匯投資除了可以有穩定的收益外，還可以幫助到有夢的大學生，所以覺得這個平台還滿特別的',
                    'imageSrc' => '/upload/2019/11/test.jpg',
                    'video_link' => null,
                    'post_title' => '台北設計業 陳先生',
                    'rank' => 'officeWorker',
                    'type' => '【借款人專訪】',
                    'amount' => 123,
                    'rate' => 321,
                    'period_range' => 12,
                    'spend_day' => 3
                ],
                2 => [
                    'ID' => 3,
                    'feedback' => '有比較過其他平台，其他的債權金額都比較大，普匯可以小額投資，還可以進行債權轉讓，讓我可以即時的拿回投資的錢',
                    'imageSrc' => null,
                    'video_link' => 'https://www.youtube.com/embed/oHg5SJYRHA0',
                    'post_title' => '銀行員 林小姐',
                    'rank' => 'student',
                    'type' => '【借款人專訪】',
                    'amount' => 123,
                    'rate' => 321,
                    'period_range' => 12,
                    'spend_day' => 3
                ],
            ];
        }
        return response()->json($experiences, 200);
    }

    public function getFeedbackImg(Request $request)
    {
        $input = $request->all();
        $feedbackImg = DB::table('feedbackImg')->select(['image'])->where('feedbackID', '=', $input['ID'])->orderBy('order', 'asc')->get();
        return response()->json($feedbackImg, 200);
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

        $banner = DB::table('banner')->select(['desktop', 'mobile', 'link'])->where([['isActive', '=', 'on'], ['type', '=', $input['filter']]])->orderBy('post_modified', 'desc')->get();

        $data[] = $bannerData[$input['filter']];

        foreach ($banner as $index => $row) {
            $data[$index + 1]['desktop'] = $row->desktop;
            $data[$index + 1]['mobile'] = $row->mobile;
            $data[$index + 1]['link'] = $row->link;
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
