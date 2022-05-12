<?php

namespace App\Console\Commands;

use App\Models\Campaign2022;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MakeMontages extends Command
{
    private const reference = 'campaign_2022';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'montages:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '製作蒙太奇頁面';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data_list = Campaign2022::where('status', '=', 0)->get(['file_name', 'user_id']);
        foreach ($data_list as $data) {
            $user_montage_res = Http::get(env('API_URL').'website/user_montage', [
                'reference' => self::reference,
                'user_id' => $data['user_id']
            ])->json();

            if ($user_montage_res['result'] != 'SUCCESS') {
                continue;
            }

            if (empty($user_montage_res['data']['status'])) {
                continue;
            }

            switch ($user_montage_res['data']['status']) {
                case 1 : // 此user_id已上傳過
                    if ($this->_update_user_montage($data['file_name'], $data['user_id']) == false) {
                        break;
                    }
                    Campaign2022::update(['id' => $data['id']], ['status' => 1]);
                    break;
                case 2: // 找不到reference的圖
                    if ($this->_new_user_montage($data['file_name'], $data['user_id']) == false) {
                        break;
                    }
                    Campaign2022::update(['id' => $data['id']], ['status' => 1]);
                    break;
                default:
                    if ($this->_new_user_montage($data['file_name'], $data['user_id']) == false) {
                        break;
                    }
                    Campaign2022::update(['id' => $data['id']], ['status' => 1]);
            }
        }
        return Command::SUCCESS;
    }

    private function _update_user_montage($file_name, $user_id): bool
    {
        $base64_img = $this->_get_base64_img($file_name);
        if ($base64_img === false) {
            return false;
        }
        $response = Http::asForm()->post(env('API_URL').'website/update_user_montage', [
            'reference' => self::reference,
            'user_id' => $user_id,
            'img' => $base64_img,
        ]);
        if ($response['result'] != 'SUCCESS') {
            return false;
        }
        return true;
    }

    private function _new_user_montage($file_name, $user_id): bool
    {
        $base64_img = $this->_get_base64_img($file_name);
        if ($base64_img === false) {
            return false;
        }
        $response = Http::asForm()->post(env('API_URL').'website/new_user_montage', [
            'reference' => self::reference,
            'user_id' => $user_id,
            'img' => $base64_img,
        ]);
        if ($response['result'] != 'SUCCESS') {
            return false;
        }
        return true;
    }

    private function _get_base64_img($file_url)
    {
        $img = file_get_contents($file_url);
        if ($img === false) {
            return false;
        }
        return base64_encode($img);
    }
}
