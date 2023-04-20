<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SendJobApplicationEmail extends RowAction
{
    public $name = '發送E-mail';

    public function handle(Model $model, Request $request)
    {
        $json_data = json_decode($model, true);
        $we_string = '';

        $work_experiences = json_decode($model['work_experiences']);
        foreach ($work_experiences as $experience) {
            foreach($experience as $key => $val) {
                if ($val == '' || $val == null) {
                    continue;
                }
                if ($key == 'companyName') {
                    $we_string = "{$we_string}公司名稱：{$val}<br>";
                }
                if ($key == 'jobDescription') {
                    $we_string = "{$we_string}工作內容：{$val}<br>";
                }
                if ($key == 'yearsOfExperience') {
                    $we_string = "{$we_string}經歷年資：{$val}<br>----------<br>";
                }
            }
        }
        $content = "
            應徵職位：{$json_data['job_position']}<br>
            姓名：{$json_data['name']}<br>
            血型：{$json_data['blood_type']}<br>
            身高：{$json_data['height']}<br>
            體重：{$json_data['weight']}<br>
            出生年月日：{$json_data['birthday']}<br>
            婚姻狀況：{$json_data['marriage']}<br>
            身份證字號：{$json_data['id_number']}<br>
            興趣、嗜好：{$json_data['hobby']}<br>
            戶籍地址：{$json_data['address']}<br>
            通訊地址：{$json_data['mailing_address']}<br>
            住家電話：{$json_data['phone']}<br>
            行動電話：{$json_data['mobile_phone']}<br>
            電子信箱：{$json_data['email']}<br>
            最高學歷：{$json_data['education']}<br>
            專長：{$json_data['expertise']}<br>
            工作經歷：{$we_string}<br>
            填表人：{$json_data['wrote_person']}<br>
            填表日期：{$json_data['wrote_date']}<br>
        ";
        
        $params = [
            'email' => $request->get('supervisor'),
            'title' => '應試者履歷',
            'content' => $content,
            'smtp_server' => 'internal'
        ];

        $endpoint = 'http://13.113.103.77:9452/cartero/api/by-email';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint.'?'.http_build_query($params));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($curl);
        curl_close($curl);

        return $this->response()->success($json_data['job_position'])->refresh();
    }

    public function form()
    {
        $supervisor = [
            'derekhwang33@gmail.com' => '純 - 設計類',
            'Yuan@influxfin.com' => '林柏元 - 設計類',
            'Nobroux@influxfin.com' => '許雲輔 - 系統開發部',
            'Timlee@influxfin.com' => '李奕伽 - 法務類',
            'tammy@influxfin.com' => '曲素玉 - 財務類',
            'mori@influxfin.com' => '林丹楓 - All',
            'arthur@influxfin.com' => 'Arthur.Y姚(台灣) - All',
            'qqq0123@influxfin.com' => '蔡佳臻 - APM',
            'ethan@influxfin.com' => '李奕賢 - 影音類',
            'michaelpeng@influxfin.com' => '彭崇博 - 業務類',
        ];

        $this->select('supervisor', '部門主管')->options($supervisor)->required();
    }

}