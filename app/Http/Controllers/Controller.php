<?php

namespace App\Http\Controllers;

use App\Helpers\Calculate;
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
                return response()->json([$e], 501);
            }

            return response()->json(['success'], 200);
        }else{
            return response()->json(['success'], 501);
        }
    }

    public function getTransferCase(Request $request){
        $input = $request->all();
        $case_data_return = [];

        // 限制案件最新五十筆
        $input['limited'] = 50;
        $params = http_build_query($input);
        $case_response = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/transfer_list?' . $params . '"');
        if(!empty($case_response)){
            $case_data = json_decode($case_response,true);
            if(isset($case_data['result']) && $case_data['result'] == 'SUCCESS' && !empty($case_data['data']['list'])){
                if(count($case_data['data']['list']) > 100){
                    for($i=0; $i<100; $i++){
                        $case_data_return[] = $case_data['data']['list'][$i];
                    }
                }else{
                    $case_data_return = $case_data['data']['list'];
                }
                return response()->json($case_data_return, 200);
            }
        }else{
            return response()->json(['not response'], 501);
        }
    }

    public function getCase(Request $request){
        $input = $request->all();
        $case_data_return = [];

        if(isset($input['product_id']) && isset($input['status'])){
            if(in_array($input['product_id'],[0,1,3]) || in_array($input['status'],[3,10])){
                // 限制案件最新五十筆
                $input['limited'] = 50;
                if($input['product_id'] == 0){
                    $input['product_id'] = 1;
                    $params = http_build_query($input);
                    $case_response_1 = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/list?' . $params . '"');

                    $input['product_id'] = 3;
                    $params = http_build_query($input);
                    $case_response_3 = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/list?' . $params . '"');
                    $case_response = '';
                    if(!empty($case_response_1) && !empty($case_response_3)){
                        $case_response_1 = json_decode($case_response_1,true);
                        $case_response_3 = json_decode($case_response_3,true);
                        if(isset($case_response_1['result']) && $case_response_1['result'] == 'SUCCESS' && isset($case_response_3['result']) && $case_response_3['result'] == 'SUCCESS'){
                            $case_response = array_merge($case_response_1['data']['list'],$case_response_3['data']['list']);

                            if(isset($input['orderby']) && !empty($input['orderby'])){
                                usort($case_response, function($a, $b) use($input){
                                    return $input['sort'] == 'desc' ?($a[$input['orderby']] - $b[$input['orderby']] < 0) :($a[$input['orderby']] - $b[$input['orderby']] > 0);
                                });
                            }
                            if(count($case_response) > 100){
                                for($i=0; $i<100; $i++){
                                    $case_data_return[] = $case_response[$i];
                                }
                            }else{
                                $case_data_return = $case_response;
                            }
                            return response()->json($case_data_return, 200);
                        }
                    }else{
                        return response()->json(['not response'], 501);
                    }
                }else{
                    $params = http_build_query($input);
                    $case_response = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/list?' . $params . '"');
                }
                if(!empty($case_response)){
                    $case_data = json_decode($case_response,true);
                    if(isset($case_data['result']) && $case_data['result'] == 'SUCCESS'){
                        if(count($case_data['data']['list']) > 100){
                            for($i=0; $i<100; $i++){
                                $case_data_return[] = $case_data['data']['list'][$i];
                            }
                        }else{
                            $case_data_return = $case_data['data']['list'];
                        }
                        return response()->json($case_data_return, 200);
                    }
                }else{
                    return response()->json(['not response'], 501);
                }
            }else{
                return response()->json(['parameter not correcct'], 501);
            }
        }else{
            return response()->json(['parameter not found'], 501);
        }
    }

    public function getOption(Request $request)
    {
        $input = $request->all();
        $response = [];
        $data = [];
        $option_config = [
            // 學校
            'school' =>'website/credit_department',
            // 科系
            'department' => 'website/credit_department'
        ];

        if(isset($input['data']) && !empty($input['data'])){
            if(isset($option_config[$input['data']])){
                $api_url = $option_config[$input['data']];
                $response = shell_exec('curl --location --request GET "' . $this->apiGetway . $api_url .'"');
            }

            if(!empty($response)){
                $response = json_decode($response,true);
                if(isset($response['data']['list'])){
                    if($input['data'] == 'school'){
                        $data = array_map(function($key) {
                            return $key;
                        }, array_keys($response['data']['list']), $response['data']['list']);
                    }
                    if($input['data'] == 'department'){
                        $data = array_map(function($key,$values) {
                            return [$key=>array_keys($values['score'])];
                        }, array_keys($response['data']['list']), $response['data']['list']);

                        $data = array_reduce($data, 'array_merge', array());
                    }
                }
                return response()->json($data, 200);
            }else{
                return response()->json(['not response'], 501);
            }
        }else{
            return response()->json(['parameter not found'], 501);
        }
    }

    // to do : 計算邏輯待拆離、利率額度待串主站
    public function getBorrowReport(Request $request)
    {
        $input = $request->all();
        $result = [
            'amount' => 0,
            'rate' => 0,
            'platform_fee' => 0,
            'repayment' => 0
        ];
        // 學生
        $student_report = [
            0 => [
                'amount' => 0,
                'rate' => 0,
                'platform_fee' => 0,
                'repayment' => 0
            ],
            1 => [
                'amount' => 0,
                'rate' => '9~13',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            2 => [
                'amount' => 0,
                'rate' => '8~12',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            3 => [
                'amount' => 0,
                'rate' => '7~11',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            4 => [
                'amount' => 0,
                'rate' => '6.5~8',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            5 => [
                'amount' => 0,
                'rate' => '5.5~7',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            6 => [
                'amount' => 0,
                'rate' => '5~6.5',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            7 => [
                'amount' => 0,
                'rate' => '4~5.5',
                'platform_fee' => 500,
                'repayment' => 0
            ]
        ];
        // 學生 0:下限分數, 1:上限分數, 2:額度
        $student_amount = [
        	[2751, 9999, 150000],
        	[2731, 2750, 148800],
        	[2711, 2730, 147600],
        	[2691, 2710, 146400],
        	[2671, 2690, 145200],
        	[2651, 2670, 144000],
        	[2631, 2650, 142800],
        	[2611, 2630, 141600],
        	[2591, 2610, 140400],
        	[2571, 2590, 139200],
        	[2551, 2570, 138000],
        	[2531, 2550, 136800],
        	[2511, 2530, 135600],
        	[2491, 2510, 134400],
        	[2471, 2490, 133200],
        	[2451, 2470, 132000],
        	[2431, 2450, 130800],
        	[2411, 2430, 129600],
        	[2391, 2410, 128400],
        	[2371, 2390, 127200],
        	[2351, 2370, 126000],
        	[2331, 2350, 124800],
        	[2311, 2330, 123600],
        	[2291, 2310, 122400],
        	[2271, 2290, 121200],
        	[2251, 2270, 120000],
        	[2231, 2250, 118800],
        	[2211, 2230, 117600],
        	[2191, 2210, 116400],
        	[2171, 2190, 115200],
        	[2151, 2170, 114000],
        	[2131, 2150, 112800],
        	[2111, 2130, 111600],
        	[2091, 2110, 110400],
        	[2071, 2090, 109200],
        	[2051, 2070, 108000],
        	[2031, 2050, 106800],
        	[2011, 2030, 105600],
        	[1991, 2010, 104400],
        	[1971, 1990, 103200],
        	[1951, 1970, 102000],
        	[1931, 1950, 100800],
        	[1911, 1930, 99600],
        	[1891, 1910, 98400],
        	[1871, 1890, 97200],
        	[1851, 1870, 96000],
        	[1831, 1850, 94800],
        	[1811, 1830, 93600],
        	[1791, 1810, 92400],
        	[1771, 1790, 91200],
        	[1751, 1770, 90000],
        	[1731, 1750, 88800],
        	[1711, 1730, 87600],
        	[1691, 1710, 86400],
        	[1671, 1690, 85200],
        	[1651, 1670, 84000],
        	[1631, 1650, 82800],
        	[1611, 1630, 81600],
        	[1591, 1610, 80400],
        	[1571, 1590, 79200],
        	[1551, 1570, 78000],
        	[1531, 1550, 76800],
        	[1511, 1530, 75600],
        	[1491, 1510, 74400],
        	[1471, 1490, 73200],
        	[1451, 1470, 72000],
        	[1431, 1450, 70800],
        	[1411, 1430, 69600],
        	[1391, 1410, 68400],
        	[1371, 1390, 67200],
        	[1351, 1370, 66000],
        	[1331, 1350, 64800],
        	[1311, 1330, 63600],
        	[1291, 1310, 62400],
        	[1271, 1290, 61200],
        	[1251, 1270, 60000],
        	[1231, 1250, 58800],
        	[1211, 1230, 57600],
        	[1191, 1210, 56400],
        	[1171, 1190, 55200],
        	[1151, 1170, 54000]
        ];
        // 上班族
        $report = [
            0 => [
                'amount' => 0,
                'rate' => 0,
                'platform_fee' => 500,
                'repayment' => 0
            ],
            1 => [
                'amount' => 80000,
                'rate' => '14~16',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            2 => [
                'amount' => 120000,
                'rate' => '10~13',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            3 => [
                'amount' => 160000,
                'rate' => '8~10',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            4 => [
                'amount' => 200000,
                'rate' => '7~8',
                'platform_fee' => 500,
                'repayment' => 0
            ],
            5 => [
                'amount' => 300000,
                'rate' => '6~7',
                'platform_fee' => 500,
                'repayment' => 0
            ]
        ];
        $total_point = 0;

        if(isset($input['identity']) && !empty($input ['identity'])){
            if(! isset($input['name']) || ! isset($input['email']) || empty($input['name']) || empty($input['email'])){
                return response()->json(['parameter must not null'], 200);
            }
            // 學生
            if($input ['identity'] == 1){
                $total_point += 1150;
                $school_points = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/credit_school"');
                $department_points = shell_exec('curl --location --request GET "' . $this->apiGetway . 'website/credit_department"');
                if(!empty($school_points) && !empty($department_points)){
                    $school_points = json_decode($school_points,true);
                    $department_points = json_decode($department_points,true);
                    if(isset($school_points['data']['list']) && !empty($school_points['data']['list']) && isset($department_points['data']['list']) && !empty($department_points['data']['list'])){
                        // 學校
                        if(isset($input['school_name'])){
                            $school_name_key = array_search($input['school_name'], array_column($school_points['data']['list'], 'name'));
                            if(isset($school_points['data']['list'][$school_name_key]['points'])){
                                $total_point += $school_points['data']['list'][$school_name_key]['points'];
                            }
                        }
                        // 科系
                        if(isset($input['department']) && isset($department_points['data']['list'][$input['school_name']]['score'][$input['department']]) ){
                            $total_point += $department_points['data']['list'][$input['school_name']]['score'][$input['department']];
                        }
                        // 是否有學貸
                        if(isset($input['is_student_loan']) && $input['is_student_loan'] == false){
                            $total_point += 50;
                        }
                        // 是否有打工或兼職
                        if(isset($input['is_part_time_job']) && $input['is_part_time_job'] == true){
                            $total_point += 100;
                        }
                        // 每月經濟收入
                        if(isset($input['monthly_economy'])){
                            switch ($input['monthly_economy']) {
                                case '>20000':
                                    $total_point += 200;
                                    break;
                                case '15000-20000':
                                    $total_point += 150;
                                    break;
                                case '10000-15000':
                                    $total_point += 100;
                                    break;
                                case '5000-10000':
                                    $total_point += 50;
                                    break;
                                default:
                                    $total_point += 0;
                                    break;
                            }
                        }
                    }
                }
                // 學生額度利率
                if ($total_point >= 2571) {
                    $result = $student_report[7];
                }elseif ($total_point >= 2371) {
                    $result = $student_report[6];
                }elseif ($total_point >= 2071) {
                    $result = $student_report[5];
                }elseif ($total_point >= 1771) {
                    $result = $student_report[4];
                }elseif ($total_point >= 1471) {
                    $result = $student_report[3];
                }elseif ($total_point >= 1171) {
                    $result = $student_report[2];
                }elseif ($total_point >= 1151) {
                    $result = $student_report[1];
                }else{
                    $result = $student_report[0];
                }

                foreach($student_amount as $key=>$values){
                    if($values[1] >= $total_point && $values[0] <= $total_point){
                        $result['amount'] = $values[2];
                        break;
                    }
                }
            }
            // 上班族
            if($input ['identity'] == 2){
                // 教育程度
                switch ($input['educational_level']) {
                    case 'below':
                        $total_point += 0;
                        break;
                    case 'bachelor':
                        $total_point += 10;
                        break;
                    case 'master':
                        $total_point += 15;
                        break;
                    case 'phD':
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
                if(is_numeric($input['insurance_salary'])){
                    if($input['insurance_salary']>=35000){
                        $total_point += 15;
                    }elseif ($input['insurance_salary']<35000 && $input['insurance_salary']>=23800) {
                        $total_point += 10;
                    }else{
                        $total_point += 0;
                    }
                }
                // 貸款餘額
                if(is_numeric($input['debt_amount']) && is_numeric($input['insurance_salary'])){
                    if($input['debt_amount'] >= $input['insurance_salary'] * 22){
                        $total_point += 0;
                    }else{
                        $total_point += 5;
                    }
                }
                // 每月攤還金額
                if(is_numeric($input['monthly_repayment']) && is_numeric($input['insurance_salary'])){
                    if($input['monthly_repayment'] >= $input['insurance_salary'] * 0.3){
                        $total_point += 5;
                    }else{
                        $total_point += 10;
                    }
                }
                // 信用卡額度
                $total_point += 10;
                // 信用卡帳單總金額
                if(is_numeric($input['creditcard_bill']) && is_numeric($input['creditcard_quota'])){
                    if($input['creditcard_bill'] > $input['creditcard_quota'] * 0.7){
                        $total_point += 5;
                    }else{
                        $total_point += 15;
                    }
                }

                // 上班族額度利率
                if($total_point >= 90){
                    $result = $report[5];
                }elseif ($total_point >= 80) {
                    $result = $report[4];
                }elseif ($total_point >= 70) {
                    $result = $report[3];
                }elseif ($total_point >= 50) {
                    $result = $report[2];
                }elseif ($total_point >= 20) {
                    $result = $report[1];
                }else {
                    $result = $report[0];
                }
            }

        }

        // 攤還金額
        if($result['amount'] != 0 && $result['rate'] != 0){
            $rate_array = explode('~',$result['rate']);
            if(isset($rate_array[0]) && isset($rate_array[1])){
                $Calculate = new Calculate();
                $repayment_1 = $Calculate->PMT_calculate($rate_array[0], 24, $result['amount']);
                $repayment_2 = $Calculate->PMT_calculate($rate_array[1], 24, $result['amount']);
                $result['repayment'] = number_format($repayment_1, 0).'~'.number_format($repayment_2, 0);
            }
        }

        // 平台手續費
        if($result['platform_fee'] != 0){
            $platform_fee_rate = 3;
            if($input ['identity'] == 1){
                $platform_fee_rate = 3;
            }

            if($input ['identity'] == 2){
                $platform_fee_rate = 4;
            }
            $platform_fee = intval(round($result['amount'] / 100 * $platform_fee_rate, 0));
            if($platform_fee > $result['platform_fee']){
                $result['platform_fee'] = $platform_fee;
            }
        }
        $result['rate'] .= '%';

        try {
            $input['total_point'] = $total_point;
            $input['amount'] = $result['amount'];
            $input['rate'] = $result['rate'];
            $input['platform_fee'] = $result['platform_fee'];
            $input['created_at'] = date('Y-m-d H:i:s', strtotime('+8 hours'));
            DB::table('borrow_report')->insert($input);
        } catch (Exception $e) {
            return response()->json($e, 400);
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
            $result = DB::table('interview')->select('*')->where('category', '=', $input['filter'])->orderBy('post_modified', 'desc')->get();
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

        if (isset($input['rank']) && !empty($input['rank'])) {
            $filter[] = ['rank', '=', $input['rank']];
        }

        $experiences = DB::table('interview')->select([
            'ID',
            'feedback',
            'imageSrc',
            'video_link',
            'post_title',
            'rank',
            'type',
            'amount',
            'rate',
            'period_range',
            'spend_day'
        ])->where($filter)->get();

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
