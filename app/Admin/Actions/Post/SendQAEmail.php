<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SendQAEmail extends RowAction
{
    public $name = '發送E-mail';

    public function handle(Model $model, Request $request)
    {
        $json_data = json_decode($model, true);

        $q_string = '';
        $questions = json_decode($model['question']);
        foreach ($questions as $question) {
            $q_string = $q_string . $question->{'q'} . ' => ' . $question->{'a'} . '<br><br>';
        }
        $content = "
            應徵職位：{$json_data['job_position']}<br>
            姓名：{$json_data['name']}<br>
            年齡：{$json_data['age']}<br>
            面試問答：<br>
            $q_string
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

        return $this->response()->success('成功發送')->refresh();
    }

    public function form()
    {
        $supervisor = [
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