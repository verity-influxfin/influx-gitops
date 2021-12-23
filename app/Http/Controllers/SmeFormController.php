<?php

namespace App\Http\Controllers;

use App\Models\SmeApply;
use App\Models\SmeConsult;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class SmeFormController extends BaseController
{
    public $operating_difficulty = []; // 目前營運上遇到的困難
    public $funds_purpose = []; // 公司資金用途
    public $financing_difficulty = []; // 融資時最常遇到的困難

    public function __construct()
    {
        $this->operating_difficulty = [
            1 => '資金周轉問題',
            2 => '經營管理問題',
            3 => '開發難度高',
            4 => '成本費用入不敷出',
            5 => '產品競爭力不足',
            6 => '其他',
        ];

        $this->funds_purpose = [
            1 => '營運周轉',
            2 => '償還借款',
            3 => '購置設備廠房',
            4 => '其他',
        ];

        $this->financing_difficulty = [
            1 => '資料提供複雜且不便利',
            2 => '核准率低',
            3 => '申請、審核時間過長',
            4 => '核准條件較差，資金成本高',
        ];
    }

    /**
     * 「立即申辦」表單
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveApplyForm(Request $request)
    {
        try {
            $input = $request->all();

            if (!isset($input['tax_id']) || empty($input['tax_id'])) {
                throw new \Exception('統一編號必填');
            }
            if (strlen($input['tax_id']) != 8) {
                throw new \Exception('統一編號格式錯誤');
            }

            if (!isset($input['company_name']) || empty($input['company_name'])) {
                throw new \Exception('公司名稱必填');
            }

            if (!isset($input['contact_person']) || empty($input['contact_person'])) {
                throw new \Exception('聯絡人必填');
            }

            if (!isset($input['contact_phone']) || empty($input['contact_phone'])) {
                throw new \Exception('連絡電話必填');
            }

            if (!isset($input['email']) || empty($input['email'])) {
                throw new \Exception('電子信箱必填');
            }

            SmeApply::insert($input);

            return response()->json(['result' => 'SUCCESS']);
        } catch (\Throwable $e) {
            return response()->json([
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 「我要諮詢」表單
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveConsultForm(Request $request)
    {
        try {
            $input = $request->all();

            if (!isset($input['tax_id']) || empty($input['tax_id'])) {
                throw new \Exception('統一編號必填');
            }
            if (strlen($input['tax_id']) != 8) {
                throw new \Exception('統一編號格式錯誤');
            }

            if (!isset($input['company_name']) || empty($input['company_name'])) {
                throw new \Exception('公司名稱必填');
            }

            if (!isset($input['operating_difficulty'])) {
                throw new \Exception('目前營運上遇到的困難必填');
            }
            if (!isset($this->operating_difficulty[$input['operating_difficulty']])) {
                $input['operating_difficulty'] = 0;
            }

            if (!isset($input['funds_purpose'])) {
                throw new \Exception('公司資金用途必填');
            }
            if (!isset($this->funds_purpose[$input['funds_purpose']])) {
                $input['funds_purpose'] = 0;
            }

            if (!isset($input['financing_difficulty'])) {
                throw new \Exception('融資時最常遇到的困難');
            }
            if (!isset($this->financing_difficulty[$input['financing_difficulty']])) {
                $input['financing_difficulty'] = 0;
            }

            if (!isset($input['contact_person']) || empty($input['contact_person'])) {
                throw new \Exception('聯絡人必填');
            }

            if (!isset($input['contact_phone']) || empty($input['contact_phone'])) {
                throw new \Exception('連絡電話必填');
            }

            if (!isset($input['email']) || empty($input['email'])) {
                throw new \Exception('電子信箱必填');
            }

            SmeConsult::insert($input);

            return response()->json(['result' => 'SUCCESS']);
        } catch (\Throwable $e) {
            return response()->json([
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 用統一編號取得公司名稱
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyName(Request $request)
    {
        try {
            $tax_id = (int) $request->input('tax_id');
            if (empty($tax_id) || strlen($tax_id) != 8) {
                return response()->json(['data' => '']);
            }

            $response = Http::get(env('API_URL').'website/company_name?tax_id='.$tax_id);

            if (isset($response['result']) && $response['result'] == 'SUCCESS') {
                return response()->json(['data' => $response['data'] ?? '']);
            }

            return response()->json(['data' => '']);
        } catch (\Throwable $e) {
            return response()->json(['data' => '']);
        }
    }
}
