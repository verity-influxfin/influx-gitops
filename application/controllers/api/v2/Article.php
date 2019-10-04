<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Article extends REST_Controller {

	public $user_info;
	public $redis=true;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('admin/article_model');
    }
	
	/**
     * @api {get} /v2/article/event 文章 最新活動
	 * @apiVersion 0.2.0
	 * @apiName GetArticleEvent
     * @apiGroup Article
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} type 類別
	 * @apiSuccess {String} title 標題
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} image_url 圖片連結
	 * @apiSuccess {String} url 連結
	 * @apiSuccess {Number} rank 排序（由大至小）
	 * @apiSuccess {Number} updated_at 最後更新時間
	 *
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"type": "event",
     * 			"list":[
     * 			{
     * 				"url": "https://dev-api.influxfin.com",
     * 				"title": "event",
     * 				"content": "<p>event event</p>",
     * 				"image_url": "https://d3imllwf4as09k.cloudfront.net/img/admin/post1550664784915.jpg",
     * 				"rank": 59,
     * 				"updated_at": 1550667400
     * 			},
     * 			{
     * 				"url": "https://dev-api.influxfin.com",
     * 				"title": "event2",
     * 				"content": "<p>Event</p>",
     * 				"image_url": "",
     * 				"rank": 55,
     * 				"updated_at": 1550667092
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function event_get()
    {
		$article_list = $this->article_model->order_by('rank','desc')->get_many_by(['type'=>1,'status'=>1]);
		$list 			= [];
		if($article_list){
			foreach($article_list as $key => $value){
				$list[] = [
					'title'		=> $value->title,
					'content'	=> $value->content,
					'image_url'	=> $value->image,
					'url'		=> $value->url,
					'rank'		=> intval($value->rank),
					'updated_at'=> intval($value->updated_at),
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['type'=>'event','list' => $list]]);
    }

	
	/**
     * @api {get} /v2/article/news 文章 最新消息
	 * @apiVersion 0.2.0
	 * @apiName GetArticleNews
     * @apiGroup Article
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} type 類別
	 * @apiSuccess {String} title 標題
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} image_url 圖片連結
	 * @apiSuccess {String} url 連結
	 * @apiSuccess {Number} rank 排序（由大至小）
	 * @apiSuccess {Number} updated_at 最後更新時間
	 *
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"type": "news",
     * 			"list":[
     * 			{
     * 				"url": "https://dev-api.influxfin.com",
     * 				"title": "News",
     * 				"content": "<p>News News</p>",
     * 				"image_url": "https://d3imllwf4as09k.cloudfront.net/img/admin/post1550664784915.jpg",
     * 				"rank": 59,
     * 				"updated_at": 1550667400
     * 			},
     * 			{
     * 				"url": "https://dev-api.influxfin.com",
     * 				"title": "News2",
     * 				"content": "<p>News</p>",
     * 				"image_url": "",
     * 				"rank": 55,
     * 				"updated_at": 1550667092
     * 			}
     * 			]
     * 		}
     * }
     */
	public function news_get()
    {
		$article_list = $this->article_model->order_by('rank','desc')->get_many_by(['type'=>2,'status'=>1]);
		$list 			= [];
		if($article_list){
			foreach($article_list as $key => $value){
				$list[] = [
					'title'		=> $value->title,
					'content'	=> $value->content,
					'image_url'	=> $value->image,
					'url'		=> $value->url,
					'rank'		=> intval($value->rank),
					'updated_at'=> intval($value->updated_at),
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['type'=>'news','list' => $list]]);
    }

    public function countDownload_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $this->load->model('behavion/beha_user_model');
        if(isset($input['promo'])){
            $query = '%first_open":"'.$input['promo'].'"%';
            $get_num = $this->beha_user_model->count_by([
                'behavior like'   => $query,
            ]);
            $this->response($get_num);
        }
    }
}
