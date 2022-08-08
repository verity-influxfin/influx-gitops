<?php

namespace Certification_ocr\Marker;

/**
 * OCR 辨識
 * Type : 標記圖片上的關鍵訊息
 * Cert : 工作收入證明
 */
class Cert_job extends Ocr_marker_base
{
    protected $task_path = '/keywords_marker/work_income_proof';
    protected $content;
    protected $task_type = self::TYPE_MARKER;

    public function __construct($certification)
    {
        parent::__construct($certification);
        if ( ! empty($this->certification['content']))
        {
            $this->content = json_decode($this->certification['content'], TRUE);
        }
        else
        {
            $this->content = [];
        }
    }

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $content = json_decode($this->certification['content'], TRUE);
        if ( ! empty($content['financial_image']))
        { // 「財務收入證明」的圖片
            return $content['financial_image'];
        }
        return [];
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        if (empty($task_res_data['img_list']))
        {
            return [];
        }

        $content = [];
        foreach ($task_res_data['img_list'] as $img)
        {
            if (empty($img['url']))
            {
                continue;
            }
            $content[] = [
                'url' => $img['url'],
                'input_kw_mat' => $img['input_kw_mat'] ?? [],
                'salary_kw_mat' => $img['salary_kw_mat'] ?? []
            ];
        }
        return $content;
    }

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array
    {
        $url_list = $this->get_image_list();
        if (empty($url_list))
        {
            return $this->return_failure('Empty image list!');
        }
        $user_id = $this->_get_user_id();
        if (empty($user_id))
        {
            return $this->return_failure('Empty user id!');
        }
        $company = $this->_get_company();
        if (empty($company))
        {
            return $this->return_failure('Empty company!');
        }
        $this->CI->load->model('user/user_model');
        $user_info = $this->CI->user_model->as_array()->get($user_id);
        if (empty($user_info) || empty($user_info['id_number']) || empty($user_info['name']))
        {
            return $this->return_failure('Cannot figure out user\'s basic info!');
        }

        return $this->return_success([
            'img_url_list' => $url_list,
            'user_id' => $user_id,
            'user_name' => $user_info['name'],
            'user_person_id' => $user_info['id_number'],
            'company_name' => $company,
            'thumbnail_len' => 2500,
            'quality_int' => 80
        ]);
    }

    /**
     * 取得公司名稱
     * @return mixed|string
     */
    private function _get_company()
    {
        return $this->content['company'] ?? '';
    }

    /**
     * 取得使用者 id
     * @return mixed|string
     */
    private function _get_user_id()
    {
        return $this->certification['user_id'] ?? '';
    }
}