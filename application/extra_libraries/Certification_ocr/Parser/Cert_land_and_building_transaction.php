<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 土地建物謄本
 */
class Cert_land_and_building_transaction extends Ocr_parser_base
{
    protected $task_path = '/home_consumer_loan/appraisal';
    protected $task_type = self::TYPE_PARSER;
    private $content;
    private $cert_instance;
    protected $retry_flag;

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
        $this->cert_instance = Certification_factory::get_instance_by_model_resource($this->certification);

        $this->set_retry_failed_scraper_task();
    }

    /**
     * 取得 OCR 子系統 port
     * @return string
     */
    protected function get_ocr_port(): string
    {
        return CERT_OCR_HOME_LOAN_PORT;
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        if (empty($task_res_data))
        {
            return [];
        }

        return $task_res_data;
    }

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array
    {
        $get_file = $this->get_pdf_url();
        if ($get_file['success'] === FALSE)
        {
            return $this->return_failure($get_file['msg']);
        }

        $target_no = $this->get_target_no();
        if (empty($target_no))
        {
            return $this->return_failure('查無案件資訊');
        }

        $user_name = $this->get_user_name();
        if (empty($user_name))
        {
            return $this->return_failure('查無使用者資訊');
        }

        $building_address = $this->get_building_address();
        if (empty($building_address))
        {
            return $this->return_failure('查無權狀資訊');
        }

        return $this->return_success([
            'pdf_url_list' => $get_file['data']['url_list'],
            'case_no' => $target_no, // targets.target_no
            'owner_str' => $user_name, // users.name
            'appraisal_date' => date('Y-m-d'),
            'building_address_str' => $building_address,
            'user_certification_id_int' => $this->certification['id']
        ]);
    }

    /**
     * 取得欲解析的 pdf 檔案 URL
     * @return array
     */
    public function get_pdf_url(): array
    {
        if (empty($this->cert_instance))
        {
            return $this->return_failure('Cannot find pdf file.');
        }
        if (empty($this->content['pdf']))
        {
            return $this->return_failure('Empty pdf file!');
        }
        return $this->return_success(['url_list' => $this->content['pdf']]);
    }

    /**
     * 取得案件 ID
     * @return string
     */
    private function get_target_no(): string
    {
        $this->CI->load->model('loan/target_model');
        return $this->CI->target_model->get_newest_no_by_condition([
            'user_id' => $this->certification['user_id'],
            'product_id' => PRODUCT_ID_HOME_LOAN,
        ]);
    }

    /**
     * 取得使用者姓名
     * @return string
     */
    private function get_user_name(): string
    {
        $this->CI->load->model('user/user_model');
        return $this->CI->user_model->get_user_name_by_id($this->certification['user_id']);
    }

    /**
     * 取得房屋所有權上的地址
     * @return string
     */
    private function get_building_address(): string
    {
        $this->cert_instance->get_dependency_certs(CERTIFICATION_STATUS_SUCCEED);
        $cert_house_deed_content = $this->cert_instance->get_dependency_cert_content(CERTIFICATION_HOUSE_DEED);
        if (empty($cert_house_deed_content['admin_edit']['address']))
        {
            return '';
        }
        return $cert_house_deed_content['admin_edit']['address'];
    }

    /**
     * 取得該徵信項的 OCR 結果
     * @param $task_id : 任務 id
     * @return array
     * @throws RequestException
     */
    public function get_ocr_task_response($task_id = ''): array
    {
        try
        {
            $building_address = $this->get_building_address();

            $get_api_url = 'http://' . $this->get_ocr_ip() . ':' . CERT_OCR_HOME_LOAN_BOOKING_PORT;
            $request = (new Client(['base_uri' => $get_api_url]))
                ->request('GET', '/home_consumer_loan/appraisal', [
                    'headers' => [
                        'accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'query' => [
                        'building_address_str' => $building_address,
                        'user_certification_id_int' => $this->certification['id'],
                        'retry_failed_scraper_task_bool' => $this->get_retry_failed_scraper_task()
                    ],
                ]);
            $res_content = $request->getBody()->getContents();
            $this->insert_log($request->getStatusCode(), $res_content);
            $result = json_decode($res_content, TRUE);

            return $this->return_success($result);
        }
        catch (BadResponseException $e)
        {
            $this->insert_log($e->getResponse()->getStatusCode(), $e->getResponse()->getBody()->getContents());
            return $this->return_failure('Exception occurred while attempting to GET task response.');
        }
        catch (\Exception $e)
        {
            $this->insert_log($e->getCode(), $e->getMessage());
            return $this->return_failure('Exception occurred while attempting to GET task response.');
        }
    }

    public function set_retry_failed_scraper_task(bool $retry_flag = FALSE)
    {
        $this->retry_flag = $retry_flag;
    }

    public function get_retry_failed_scraper_task()
    {
        return $this->retry_flag;
    }
}