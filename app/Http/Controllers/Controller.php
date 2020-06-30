<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
            $data = json_decode(file_get_contents('data/articledata.json'), true);
            foreach ($data['video'] as $key => $row) {
                if ($row['category'] == $input['filter']) {
                    $result[] = $row;
                }
            }
        } else {
            $result = DB::table('knowledge_article')->select('*')->where([['type', '=', 'video'], ['status', '=', 'publish']])->orderBy('post_modified', 'desc')->get();
        }

        return response()->json($result, 200);
    }

    public function getNewsData(Request $request)
    {

        $curlScrapedNewsPage = shell_exec('curl -X GET "https://stage-api.influxfin.com/api/v2/article/news"');
        $newsdata = json_decode($curlScrapedNewsPage, true);

        $curlScrapedEventPage = shell_exec('curl -X GET "https://stage-api.influxfin.com/api/v2/article/event"');
        $eventdata = json_decode($curlScrapedEventPage, true);

        $result = array();

        foreach ($newsdata['data']['list'] as $index => $value) {
            $value['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
            $result[] = $value;
        }

        foreach ($eventdata['data']['list'] as $index => $value) {
            $value['updated_at'] = date('Y-m-d H:i:s', $value['updated_at']);
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
        $data = json_decode(file_get_contents('data/articledata.json'), true);

        return response()->json($data['investtonic'], 200);
    }

    public function getArticleData(Request $request)
    {
        $input = $request->all();

        @list($type, $params) = explode('-', $input['filter']);
        $result = [];
        if ($type === 'knowledge') {
            $result = DB::table('knowledge_article')->select('*')->where('ID', '=', $params)->orderBy('post_modified', 'desc')->first();
        } else{
            $data = json_decode(file_get_contents('data/articledata.json'), true);
            $result = $data[$type][$params - 1];
        }


        return response()->json($result, 200);
    }

    public function getVideoPage(Request $request)
    {
        $input = $request->all();

        $data = json_decode(file_get_contents('data/articledata.json'), true);

        $knowledge = DB::table('knowledge_article')->select('*')->where('ID', '=', $input['filter'])->orderBy('post_modified', 'desc')->first();

        return response()->json($knowledge, 200);
    }

    public function getInterviewData(Request $request)
    {
        $data = json_decode(file_get_contents('data/data.json'), true);

        return response()->json($data, 200);
    }

    public function getExperiencesData(Request $request)
    {
        $data = json_decode(file_get_contents('data/experiencesData.json'), true);

        return response()->json($data, 200);
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
        $data = json_decode(file_get_contents('data/mobileData.json'), true);

        return response()->json($data, 200);
    }

    public function getMilestoneData(Request $request)
    {
        $data = json_decode(file_get_contents('data/milestoneData.json'), true);

        return response()->json($data, 200);
    }

    public function getMediaData(Request $request)
    {
        $data = json_decode(file_get_contents('data/mediaData.json'), true);

        return response()->json($data, 200);
    }

    public function getPartnerData(Request $request)
    {
        $data = json_decode(file_get_contents('data/partnerData.json'), true);

        return response()->json($data, 200);
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
}
