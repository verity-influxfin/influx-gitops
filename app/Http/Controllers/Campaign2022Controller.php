<?php
/**
 * 活動頁API：普匯五週年 邀你一起跳耀世界
 */

namespace App\Http\Controllers;

use App\Models\Campaign2022;
use App\Models\Campaign2022_vote;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Campaign2022Controller extends Controller
{
    // 每頁呈現的作品數
    private const num_per_page = 6;
    // 每人每日總票數
    private const max_votes_per_day = 3;

    // 取得全部作品列表
    public function get_all(): JsonResponse
    {
        $result = Campaign2022::rankingDesc()->getColumns()->get();
        return $this->_return_success($result);
    }

    // 取得特定作品資料
    public function get_one($id): JsonResponse
    {
        $result = Campaign2022::where('id', $id)->getColumns()->first();
        return $this->_return_success($result);
    }

    // 取得特定分頁的作品列表
    public function get_by_page($page): JsonResponse
    {
        if ($page <= 0) {
            $skip = 0;
        } else {
            $skip = ($page - 1) * self::num_per_page;
        }
        $result = [
            'list' => Campaign2022::rankingDesc()
                ->pagination($skip, self::num_per_page)
                ->getColumns()
                ->get(),
            'total' => Campaign2022::count()
        ];
        return $this->_return_success($result);
    }

    // 取得特定關鍵字的作品列表
    public function get_by_keyword($keyword, $page): JsonResponse
    {
        if ($page <= 0) {
            $skip = 0;
        } else {
            $skip = ($page - 1) * self::num_per_page;
        }
        $result = [
            'list' => Campaign2022::rankingDesc()
                ->pagination($skip, self::num_per_page)
                ->keyword($keyword)
                ->getColumns()
                ->get(),
            'total' => Campaign2022::count()
        ];
        return $this->_return_success($result);
    }

    // 使用者投票
    public function save_vote(Request $request): JsonResponse
    {
        $response = $this->_connect_deus('GET');
        if ($response['status'] != 200) {
            return $this->_return_failed($response['data'], $response['status']);
        }
        // 驗單日單人總投票數
        $votes = Campaign2022_vote::where('vote_from', $response['data']['id'])
            ->whereBetween(DB::raw('UNIX_TIMESTAMP(created_at)'), [strtotime('today'), strtotime('tomorrow')])
            ->count();
        if ($votes >= self::max_votes_per_day) {
            return $this->_return_success([], '今日已投滿3票');
        }
        // 驗作品資料
        $inputs = $request->all();
        if (empty($inputs['id'])) {
            return $this->_return_failed('無此作品');
        }
        // 寫入資料庫
        try {
            DB::beginTransaction();
            $campaign2022 = Campaign2022::lockForUpdate()->find($inputs['id']);
            if (empty($campaign2022['user_id'])) {
                return $this->_return_failed('無此作品');
            }

            Campaign2022_vote::create([
                'vote_from' => $response['data']['id'],
                'vote_to' => $campaign2022['user_id']
            ]);
            $campaign2022::where('id', $inputs['id'])->update([
                'votes' => DB::raw('votes+1')
            ]);

            DB::commit();
            return $this->_return_success([], '投票成功，您今日已成功投了' . ($votes + 1) . '票', 201);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->_return_failed($e->getMessage(), 500);
        }
    }

    // 使用者上傳檔案
    public function save_file(Request $request): JsonResponse
    {
        $response = $this->_connect_deus('GET');
        if ($response['status'] != 200) {
            return $this->_return_failed($response['data'], $response['status']);
        }
        // 驗上傳檔案
        if (!$request->hasFile('file')) {
            return $this->_return_failed('上傳失敗');
        }
        $file = $request->file('file');
        $mime_type = $file->getClientOriginalExtension();
        $allow_type = config('verifyfiletype')['image'];
        if (!in_array(strtolower($mime_type), $allow_type)) {
            return $this->_return_failed('上傳失敗，請確認檔案為圖片');
        }
        if (!$file->isValid()) {
            return $this->_return_failed('上傳失敗');
        }
        // 寫入資料庫
        try {
            $filename = $response['data']['id'].strtotime('now').'.'.$mime_type;
            $file->move('upload/campaign2022', $filename);
            $inputs = $request->all();
            Campaign2022::updateOrCreate(['user_id' => $response['data']['id']], [
                'user_id' => $response['data']['id'],
                'nick_name' => $inputs['nick_name'] ?? '',
                'file_name' => $filename,
                'votes' => 0,
                'status' => 0
            ]);
            return $this->_return_success([], '上傳成功', 201);
        } catch (\Exception $e) {
            return $this->_return_failed($e->getMessage(), 500);
        }
    }

    public function get_montage()
    {
        $montage_res = Http::get(env('API_URL').'website/montage', [
            'reference' => 'campaign2022'
        ])->json();

        if ($montage_res['result'] == 'SUCCESS') {
            return $this->_return_success($montage_res['data']);
        }

        return $this->_return_failed('fail');
    }

    private function _connect_deus($method)
    {

        $response = (new Client())
            ->request($method, env('API_URL').'user/info', [
                'headers' => [
                    'request_token' => Session::get('token')
                ]
            ])
            ->getBody();
        $response = json_decode($response, true);

        if (!empty($response['error'])) {
            switch ($response['error']) {
                case 100:
                    return ['data' => '無效的Token', 'status' => 401];
                default:
                    return ['data' => '系統忙碌中，請稍後再試', 'status' => 400];
            }
        }

        return ['data' => $response['data'] ?? [], 'status' => 200];
    }

    private function _return_success($data = [], string $msg = null, $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'msg' => $msg], $status);
    }

    private function _return_failed(string $msg, $status = 400): JsonResponse
    {
        return response()->json(['success' => false, 'data' => null, 'msg' => $msg], $status);
    }
}
