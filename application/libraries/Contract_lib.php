<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/contract_model');
		$this->CI->load->model('admin/contract_format_model');
    }

    //一掰合約
	public function get_contract($contract_id,$user_info=[],$nodate=true){
		if($contract_id){
			$contract = $this->CI->contract_model->get($contract_id);
			if($contract){
				$format = $this->CI->contract_format_model->get($contract->format_id);
				if($format){
                    $contents   = false;
					$content 	= json_decode($contract->content,TRUE);
                    if($contract->format_id == 4){
                        $contract_part = '';
                        $first_content = '新台幣（以下同）';
                        $part          = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>'trans_part']);
                        foreach($content[4] as $key => $value){
                            $key > 0 ? $first_content = '' : null;
                            $addpart = [
                                $value[0],
                                $value[1],
                                $value[2],
                                $value[3],
                                $value[4],
                                $value[5],
                                $value[6],
                                $value[7],
                            ];
                            $part_text = $value[8]==0 ? mb_ereg_replace('已／|不併同／', '', $part->content) : mb_ereg_replace('／未|／併同', '', $part->content);
                            $contract_part .= vsprintf($part_text, $addpart);
                            if(isset($user_info[$value[1]])){
                                $contract_part = preg_replace('/與借款人即/u', '與借款人 '.get_hidden_name($user_info[$value[1]]->name).'，身分證字號 '.get_hidden_id($user_info[$value[1]]->id_number).' ，', $contract_part);
                            }
                        }
                        $contents = [
                            $content[0],
                            $content[1],
                            '',//甲方
                            $content[3],
                            $contract_part,
                            $content[5],
                            $content[6],
                            $content[7],
                            $content[8]
                        ];
                    }
                    $contents 	= vsprintf($format->content,($contents?$contents:$content));
                               if(isset($user_info[$content[0]])){
                        $contents = preg_replace('/讓與人，/u', '讓與人 '.get_hidden_name($user_info[$content[0]]->name).'，身分證字號 '.get_hidden_id($user_info[$content[0]]->id_number).' ，', $contents);
                    }
                    if(isset($user_info[$content[1]])){
                        $contents = preg_replace('/受讓人，/u', '受讓人 '.get_hidden_name($user_info[$content[1]]->name).'，身分證字號 '.get_hidden_id($user_info[$content[1]]->id_number).' ，', $contents);
                    }
                    $nodate?$contents 	.= "\n 中華民國 ".(date('Y',$contract->created_at)-1911).' '.date('年 m 月 d 日',$contract->created_at):'';
					$data = array(
						'title'		=> $format->title,
						'content' 	=> $contents,
						'created_at'=> $contract->created_at,
					);
					return $data;
				}
			}
		}
		return false;
	}
	
	public function sign_contract( $type='' , $data = [] ){
		if($type && $data){
			$format = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>$type]);
			if($format){
				$param = array(
					'type'		=> $type,
					'format_id'	=> $format->id,
					'content' 	=> json_encode($data) 
				);
				return $this->CI->contract_model->insert($param);
			}
		}
		return false;
	}

	public function update_contract( $id=0 , $data = [] ){
		if($id && $data){
			$param = array(
				'content' 	=> json_encode($data) 
			);
			return $this->CI->contract_model->update($id,$param);
		}
		return false;
	}
	
	public function pretransfer_contract($type='' , $content = [] ,$user_info=[]){
		if($content){
			$format = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>$type]);
			$type=='trans_multi'?$content[2]='':'';
			if($format){
				$contract 	= vsprintf($format->content,$content);
				$contract 	.= "\n 中華民國 ".(date('Y')-1911).' '.date('年 m 月 d 日');
				return $contract;
			}
		}
		return false;
	}

	//債轉合約
    public function build_contract($type,$user_id,$trans_user_id,$data,$data_arr,$count,$amount,$index=0,$view=0){
        if($type=='transfer'){
            $contract_var = [
                $user_id,
                $trans_user_id,
                $data_arr['user_id'][$index],
                $data_arr['target_no'][$index],
                $data_arr['principal'][$index],
                $data_arr['principal'][$index],
                $count==1&&$amount!=0?$amount:$data_arr['principal'][$index],
                $data_arr['user_id'][$index],
                $data_arr['user_id'][$index],
                $data_arr['user_id'][$index],
            ];
            return $view==1?$this->pretransfer_contract('transfer',$contract_var):$contract_var;
        }elseif($type=='trans_multi'){
            $contract_part    = '';
            $contract_arr     = [];
            $first_content    = '新台幣（以下同）';
            $normal_principal = 0;
            $normal_interest  = 0;
            $delay_principal  = 0;
            $part             = $this->CI->contract_format_model->order_by('created_at','desc')->get_by(['type'=>'trans_part']);
            for ($i = 0; $i < $count; $i++){
                $delay_target = $data['delay_interest'][$i]==0?0:1;
                if($delay_target==0){
                    $normal_principal += $data_arr['principal'][$i];
                    $normal_interest  += $data_arr['interest'][$i];
                }
                else{
                    $delay_principal  += $data_arr['principal'][$i]+$data_arr['interest'][$i]+$data_arr['delay_interest'][$i];
                }
                if($view==1){
                    $part_text = $delay_target==0?mb_ereg_replace('已／|不併同／','',$part->content):mb_ereg_replace('／未|／併同','',$part->content);
                    $i>0?$first_content='':null;
                }
                $addpart = [
                    $i+1,
                    $data_arr['user_id'][$i],
                    $data_arr['target_no'][$i],
                    ($i == 0&&$view==1?$first_content.$data_arr['loan_amount'][$i]:$data_arr['loan_amount'][$i]),
                    $data_arr['principal'][$i],
                    $data_arr['interest_rate'][$i],
                    $data_arr['interest'][$i] + $data_arr['delay_interest'][$i],
                    DELAY_INTEREST,
                    $delay_target
                ];
                if($view==1) {
                    $contract_part .= vsprintf($part_text,$addpart);
                }else{
                    $contract_arr[] = $addpart;
                }
            }
            $contract_var = [
                $user_id,
                $trans_user_id,
                $user_id,
                $count,
                $view==1?$contract_part:$contract_arr,
                $normal_principal,
                $normal_interest,
                $delay_principal,
                $amount==0?$data['principal']:$amount
            ];
            return $view==1?$this->pretransfer_contract('trans_multi',$contract_var):$contract_var;
        }
    }
    public function raw_contract($contract_id){
        return $this->CI->contract_model->get($contract_id);
	}
}
