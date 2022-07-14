<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampusAmbassadorIndividual2022SignupRequest;
use App\Http\Requests\CampusAmbassadorGroup2022SignupRequest;
use App\Models\CampusAmbassador2022;
use App\Models\CampusAmbassadorProposal2022;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CampusAmbassador2022Controller extends Controller
{
    private $file_list;
    private $base_upload_dir = 'upload/campus_ambassador/2022';
    const IDENTITY_INDIVIDUAL = 1;
    const IDENTITY_GROUP_LEADER = 2;
    const IDENTITY_GROUP_MEMBER = 3;
    public $identity_list = [
        self::IDENTITY_INDIVIDUAL => '個人組',
        self::IDENTITY_GROUP_LEADER => '團體組組長',
        self::IDENTITY_GROUP_MEMBER => '團體組組員',
    ];
    public $grade_list = [
        1 => '大一',
        2 => '大二',
        3 => '大三',
        4 => '大四',
        5 => '延畢',
        6 => '研究所',
        7 => '其他',
    ];
    public $qa1_list = [
        1 => '普匯的定義：普惠金融，匯集人才',
        2 => '台唯一榮獲金控創投投資的金融科技公司',
        3 => '以上皆是',
    ];
    public $qa2_list = [
        1 => '學生貸額度最高15萬，利率最低4%',
        2 => '準備學生證、雙證件、金融帳號，使用手機APP即可申請',
        3 => '以上皆是',
    ];
    public $qa3_list = [
        1 => '新北產業發展論壇',
        2 => '足球論壇',
        3 => '歌唱論壇',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->file_list = [];
    }

    public function sign_up_individual(CampusAmbassadorIndividual2022SignupRequest $request): JsonResponse
    {
        try {
            $input = $request->validated();
            // 檢查上傳檔案
            $file_err_msg = $this->_chk_file($request, array_merge(
                $this->_get_upload_list_proposal(),
                $this->_get_upload_list_individual()
            ));
            if (!empty($file_err_msg)) {
                return $this->_return_failed('檔案上傳失敗', $file_err_msg);
            }
            DB::beginTransaction();
            // 寫入提案作品資料
            $proposal = CampusAmbassadorProposal2022::create([
                'proposal' => $this->_upload_file($input['phone'], 'proposal'),
                'portfolio' => $this->_upload_file($input['phone'], 'portfolio'),
                'video' => $this->_upload_file($input['phone'], 'video'),
                'video_link' => $input['video_link'] ?? null,
            ]);
            // 寫入大使資料
            $ambassador = CampusAmbassador2022::create([
                'agree' => $input['agree'] ? 1 : 0,
                'identity' => self::IDENTITY_INDIVIDUAL,
                'proposal_id' => $proposal['id'],
                'name' => $input['name'],
                'birthday' => $input['birthday'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'school' => $input['school'],
                'major' => $input['major'],
                'grade' => $input['grade'],
                'school_city' => $input['school_city'],
                'social' => $input['social'],
                'introduction_brief' => $input['introduction_brief'],
                'introduction' => $input['introduction'],
                'qa_1' => $input['qa_1'],
                'qa_2' => $input['qa_2'],
                'qa_3' => $input['qa_3'],
                'created_ip' => $request->ip(),
            ]);
            CampusAmbassador2022::where('id', $ambassador['phone'])->update([
                'photo' => $this->_upload_file($ambassador['phone'], 'photo', 'individual')
            ]);
            DB::commit();
            return $this->_return_success(['name' => $ambassador['name']], '報名成功', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if (!empty($ambassador['phone'])) {
                $this->_remove_files("{$this->base_upload_dir}/proposal/{$ambassador['phone']}");
                $this->_remove_files("{$this->base_upload_dir}/individual/{$ambassador['phone']}");
            }
            return $this->_return_failed($e->getMessage(), null, 500);
        }
    }

    public function sign_up_group(CampusAmbassadorGroup2022SignupRequest $request): JsonResponse
    {
        try {
            $input = $request->validated();
            // 檢查上傳檔案
            $file_err_msg = $this->_chk_file($request, array_merge(
                $this->_get_upload_list_proposal(),
                $this->_get_upload_list_individual()
            ));
            if (!empty($file_err_msg)) {
                return $this->_return_failed('檔案上傳失敗', $file_err_msg);
            }
            DB::beginTransaction();
            // 寫入提案作品資料
            $proposal = CampusAmbassadorProposal2022::where('group_name', $input['group_name'])
                ->orderBy('created_at', 'desc')
                ->first();
            if (empty($proposal)) {
                $proposal = CampusAmbassadorProposal2022::create(['group_name' => $input['group_name']]);
            }
            if ($input['leader']) {
                CampusAmbassadorProposal2022::where('id', $proposal['id'])->update([
                    'proposal' => $this->_upload_file($input['phone'], 'proposal'),
                    'portfolio' => $this->_upload_file($input['phone'], 'portfolio'),
                    'video' => $this->_upload_file($input['phone'], 'video'),
                    'video_link' => $input['video_link'] ?? null,
                ]);
            }
            // 寫入大使資料
            $ambassador = CampusAmbassador2022::create([
                'agree' => $input['agree'] ? 1 : 0,
                'identity' => $input['leader'] ? self::IDENTITY_GROUP_LEADER : self::IDENTITY_GROUP_MEMBER,
                'proposal_id' => $proposal['id'],
                'name' => $input['name'],
                'birthday' => $input['birthday'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'school' => $input['school'],
                'major' => $input['major'],
                'grade' => $input['grade'],
                'school_city' => $input['school_city'],
                'social' => $input['social'],
                'introduction_brief' => $input['introduction_brief'],
                'introduction' => $input['introduction'],
                'qa_1' => $input['qa_1'],
                'qa_2' => $input['qa_2'],
                'qa_3' => $input['qa_3'],
                'created_ip' => $request->ip(),
            ]);
            CampusAmbassador2022::where('id', $ambassador['phone'])->update([
                'photo' => $this->_upload_file($ambassador['phone'], 'photo', 'individual')
            ]);
            DB::commit();
            return $this->_return_success(['group_name' => $proposal['group_name'], 'name' => $ambassador['name']], '報名成功', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if (!empty($ambassador['phone'])) {
                $this->_remove_files("{$this->base_upload_dir}/proposal/{$ambassador['phone']}");
                $this->_remove_files("{$this->base_upload_dir}/individual/{$ambassador['phone']}");
            }
            return $this->_return_failed($e->getMessage(), null, 500);
        }
    }

    /**
     * 移除目錄及其下所有檔案
     * @param $dir : 目錄路徑
     * @return void
     */
    private function _remove_files($dir)
    {
        if (is_dir($dir)) {
            $open_dir = opendir($dir);
            while ($file = readdir($open_dir)) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (!is_dir($dir . '/' . $file)) {
                    unlink($dir . '/' . $file);
                } else {
                    $this->_remove_files($dir . '/' . $file);
                }
            }
            closedir($open_dir);
            rmdir($dir);
        }
    }

    /**
     * 上傳檔案
     * @param string $phone
     * @param string $file_list_index
     * @param string $type
     * @return string|null
     */
    private function _upload_file(string $phone, string $file_list_index, string $type = 'proposal'): ?string
    {
        if (!isset($this->file_list[$file_list_index]['file'])) {
            return null;
        }
        $file = $this->file_list[$file_list_index]['file'];
        $filename = ($this->file_list[$file_list_index]['name'] ?? $file_list_index) . "_{$phone}." . $file->getClientOriginalExtension();
        $file->move("{$this->base_upload_dir}/{$type}/{$phone}", $filename);

        return $filename;
    }

    /**
     * 上傳檔案的基本檢查
     * @param $request : Illuminate\Http\Request
     * @param $upload_file_list : 欲檢查的 $request key
     * @return array
     */
    private function _chk_file($request, $upload_file_list): array
    {
        $result = [];
        foreach ($upload_file_list as $key => $value) {
            $file = $request->file($key);
            if (empty($file)) {
                continue;
            }
            if ($file->isValid() === TRUE) {
                $this->file_list[$key] = ['file' => $file, 'name' => $value];
                continue;
            }
            $result[$key] = $value . '上傳失敗';
        }
        return $result;
    }

    private function _get_upload_list_individual(): array
    {
        return [
            'photo' => '生活照',
        ];
    }

    private function _get_upload_list_proposal(): array
    {
        return [
            'proposal' => '校園推廣企劃提案',
            'portfolio' => '作品集',
            'video' => '影片上傳',
        ];
    }

    private function _return_success($data = [], string $msg = null, $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'msg' => $msg], $status);
    }

    private function _return_failed(string $msg, $data = null, $status = 400): JsonResponse
    {
        return response()->json(['success' => false, 'msg' => $msg, 'data' => $data], $status);
    }
}
