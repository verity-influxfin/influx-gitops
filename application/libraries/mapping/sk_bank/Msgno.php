<?php
class Msgno
{

	// 回應碼對應訊息
	public $code_to_msg = [
		'200' => '新光案件資訊取得成功',
		'201' => '交易序號生成成功',
		'202' => '查找當前送出紀錄並已生成新交易序號',
		'403' => '缺少參數 : target id (平台案件號)',
		'404' => '查無送出紀錄',
		'405' => '該案件已送新光並已受理'
	];

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * [getSKBankInfoByTargetId 拿取對應平台案件 ID之新光資訊]
	 * @param  string $target_id           [平台案件 ID]
	 * @return array  $response            [回應]
	 * (
	 *  [status] =>
	 *    (
	 *     [code] => 回應碼
	 *     [message] => 回應碼訊息
	 *    )
	 *  [data] =>
	 *   (
	 *    [msg_no] => 交易編號
	 *    [send_log] => 送出紀錄
	 *   )
	 * )
	 */
	// to do : 待擴充圖片附件檢核表撈取
    public function getSKBankInfoByTargetId($target_id = '', $type = 'text', $bank = MAPPING_MSG_NO_BANK_NUM_SKBANK)
    {
		$response = [
			'status' => [
				'code' => '403',
				'message' => '',
			],
			'data' => [
				'msg_no' => '',
				'send_log' => [],
			],
		];

		if($target_id && $type){
			// 查詢關聯表是否有該案件號對應之新光交易序號
			// to do : 待加入附件檢核表資訊
			$this->CI->load->model('skbank/LoanTargetMappingMsgNo_model');
            $mapping_info = $this->CI->LoanTargetMappingMsgNo_model->order_by('id','desc')->get_by(['target_id'=>$target_id, 'type'=> $type, 'bank' => $bank]);
			if($mapping_info){
				$msg_no = $mapping_info->msg_no ?? '';
				$action_user = $mapping_info->action_user_id ?? '';
			}else{
				$msg_no = '';
				$action_user = '';
			}
			if($msg_no){
				$this->CI->load->model('skbank/LoanSendRequestLog_model');
				$mapping_info = $this->CI->LoanSendRequestLog_model->get_by(['msg_no'=>$msg_no, 'send_success !='=>0, 'case_no !='=>0 ]);
				// 送出人員
				if($action_user){
					$this->CI->load->model('admin/admin_model');
					$admin_info	= $this->CI->admin_model->get_by(['id'=>$action_user]);
					if($admin_info){
						$action_user = $admin_info->name ?? '';
					}
				}

				if($mapping_info){
					$response['status']['code'] = 405;
					$response['data']['send_log'] = json_decode(json_encode($mapping_info),true);
					$response['data']['send_log']['action_user'] = $action_user;
				}else{
					$mapping_info = $this->CI->LoanSendRequestLog_model->order_by('created_at','desc')->get_by(['msg_no'=>$msg_no]);
					$response['status']['code'] = 202;
					$response['data']['msg_no'] = $this->createNewMsgNo();
					$response['data']['send_log'] = json_decode(json_encode($mapping_info),true);
					$response['data']['send_log']['action_user'] = $action_user;
				}
			}else{
				$response['status']['code'] = 201;
				$response['data']['msg_no'] = $this->createNewMsgNo();
			}
		}

		if(isset($this->code_to_msg[$response['status']['code']])){
			$response['status']['message'] = $this->code_to_msg[$response['status']['code']];
		}

		return $response;
	}

	/**
	 * [createNewMsgNo 生成新的交易序號]
	 * @return $msg_no [新光交易序號]
	 */
	public function createNewMsgNo(){
		$msg_no = '';
		$date = date('Ymd');
		$count = '0000000';

		$this->CI->load->model('skbank/LoanTargetMappingMsgNo_model');
		$serial_number = $this->CI->LoanTargetMappingMsgNo_model->getMaxSerialNumberByDate($date);
		if($serial_number != ''){
			// to do : 加入序號上限阻擋
			if($serial_number == '9999999'){

			}
			$last_serial_number = isset($serial_number) ? $serial_number : $count;
			$count = sprintf("%07d",$last_serial_number + 1);
		}
		$msg_no = $date.$count;

		return $msg_no;
	}
}
