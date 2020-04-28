<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getListData(Request $request){
        $data = json_decode(file_get_contents('data/listData.json'),true);
        return response()->json($data);
    }

    public function getExperiencesData(Request $request){
        $data = json_decode(file_get_contents('data/experiencesData.json'),true);
        return response()->json($data);
    }

    public function getKnowledgeData(Request $request){
        $data = json_decode(file_get_contents('data/knowledgeData.json'),true);
        return response()->json($data);
    }

    public function getSharesData(Request $request){
        $input = $request->all();

        $data = json_decode(file_get_contents('data/shareData.json'),true);

        $result = [];

        foreach($data as $key => $row){
            if($input['filter'] === 'other'){
                if($row['category'] !=='share'){
                    $result[] = $row;
                }
            }else{
                if($row['category'] === $input['filter']){
                    $result[] = $row;
                }
            }
        }

        return response()->json($result);
    }

    public function getInterviewData(Request $request){
        $data = json_decode(file_get_contents('data/data.json'),true);
        return response()->json($data);
    }

    public function getNewsData(Request $request){
        $data = json_decode(file_get_contents('data/newsData.json'),true);
        return response()->json($data);
    }

    public function getServiceData(Request $request){
        $data = json_decode(file_get_contents('data/serviceData.json'),true);
        return response()->json($data);
    }

    public function getQaData(Request $request){
        $input = $request->all();
        $data = json_decode(file_get_contents('data/qaData.json'),true);

        return response()->json($data[$input['filter']]);
    }

    public function getMobileData(Request $request){
        $data = json_decode(file_get_contents('data/mobileData.json'),true);

        return response()->json($data);
    }

    public function getMilestoneData(Request $request){
        $data = json_decode(file_get_contents('data/milestoneData.json'),true);

        return response()->json($data);
    }

    public function getMediaData(Request $request){
        $data = json_decode(file_get_contents('data/mediaData.json'),true);

        return response()->json($data);
    }

    public function getPartnerData(Request $request){
        $data = json_decode(file_get_contents('data/partnerData.json'),true);

        return response()->json($data);
    }

    public function getBannerData(Request $request){
        $input = $request->all();

        $data = json_decode(file_get_contents('data/bannerData.json'),true);

        return response()->json($data[$input['filter']]);
    }

    public function getApplydata(Request $request){
        $input = $request->all();

        $data = json_decode(file_get_contents('data/applyData.json'),true);

        return response()->json($data[$input['filter']]);
    }

    public function getInvestTonicData(Request $request){
        $data = json_decode(file_get_contents('data/investTonicData.json'),true);

        return response()->json($data);
    }

    public function getReportData(Request $request){
        $input = $request->all();
        $data = json_decode(file_get_contents('data/reportData.json'),true);

        return response()->json($data[$input['filter']]);
    }
}
