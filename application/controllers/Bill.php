<?php


class Bill extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BillModel', 'UserJoinModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/bill/index');
	}

	public function getBillList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$claim_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : date('Y', time());
		$claim_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : date('m', time());
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';


		$send_data = array(
			'claim_year'=>$claim_year,
			'claim_month'=>$claim_month,
			'room_num' => $room_num,
			'building_seq' => $building_seq
		);

		$billListInfo = $this->BillModel->getBillList($send_data);

		foreach($billListInfo as $key => $val){
			$billListInfo[$key]['lm_charge_ym'] = $val['lm_charge_year']."-".$val['lm_charge_month'];
		}


		$return_arr = json_encode($billListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getBillInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$claim_year = (!empty($_REQUEST['charge_year'])) ? $_REQUEST['charge_year'] : '';
		$claim_month = (!empty($_REQUEST['charge_month'])) ? $_REQUEST['charge_month'] : '';

		$billIndiInfo = $this->BillModel->getBillInfoIndi($building_name, $building_seq, $room_num, $claim_year, $claim_month);


		$return_arr = array();



		foreach($billIndiInfo as $key => $val){
			$gubun = $val['lm_usage_gubun'];

			if($gubun == 'A'){
				$return_arr['electro_cost'] = $val['lm_usage_cost'];
			}elseif($gubun == 'B'){
				$return_arr['water_cost'] = $val['lm_usage_cost'];
			}elseif($gubun == 'C'){
				$return_arr['gas_cost'] = $val['lm_usage_cost'];
			}elseif($gubun == 'D'){
				$return_arr['etc_cost'] = $val['lm_usage_cost'];
			}
		}
		$return_arr['building_name'] = $billIndiInfo[0]['building_name'];
		$return_arr['lm_user_seq'] = $billIndiInfo[0]['lm_user_seq'];
		$return_arr['lm_room_num'] = $billIndiInfo[0]['lm_room_num'];
		$return_arr['room_deposit'] = $billIndiInfo[0]['room_deposit']; //보증금
		$return_arr['room_monthly_fee'] = $billIndiInfo[0]['room_monthly_fee']; //월세
		$return_arr['room_maintenance_charge'] = $billIndiInfo[0]['room_maintenance_charge']; //관리비
		$return_arr['room_etc_fee'] = $billIndiInfo[0]['room_etc_fee']; //기타비
		$return_arr['total_arrears'] = 0; //총체납액
		$return_arr['lm_charge_year'] = $billIndiInfo[0]['lm_charge_year'];
		$return_arr['lm_charge_month'] = $billIndiInfo[0]['lm_charge_month'];
		$return_arr['lm_building_seq'] = $billIndiInfo[0]['lm_building_seq'];

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);


		echo $return_arr;
	}

	public function modBillInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$lm_building_seq = (!empty($_REQUEST['lm_building_seq'])) ? $_REQUEST['lm_building_seq'] : '';
		$lm_room_num = (!empty($_REQUEST['lm_room_num'])) ? $_REQUEST['lm_room_num'] : '';
		$lm_bill_year = (!empty($_REQUEST['lm_bill_year'])) ? $_REQUEST['lm_bill_year'] : '';
		$lm_bill_month = (!empty($_REQUEST['lm_bill_month'])) ? $_REQUEST['lm_bill_month'] : '';
		$lm_rental_fee = (!empty($_REQUEST['lm_rental_fee'])) ? $_REQUEST['lm_rental_fee'] : '0';
		$lm_maintenanace_fee = (!empty($_REQUEST['lm_maintenanace_fee'])) ? $_REQUEST['lm_maintenanace_fee'] : '0';
		$lm_etc_fee = (!empty($_REQUEST['lm_etc_fee'])) ? $_REQUEST['lm_etc_fee'] : '0';
		$lm_electro_fee = (!empty($_REQUEST['lm_electro_fee'])) ? $_REQUEST['lm_electro_fee'] : '0';
		$lm_water_fee = (!empty($_REQUEST['lm_water_fee'])) ? $_REQUEST['lm_water_fee'] : '0';
		$lm_gas_fee = (!empty($_REQUEST['lm_gas_fee'])) ? $_REQUEST['lm_gas_fee'] : '0';
		$lm_etc_cost = (!empty($_REQUEST['lm_etc_cost'])) ? $_REQUEST['lm_etc_cost'] : '0';
		$lm_arrears_fee = (!empty($_REQUEST['lm_arrears_fee'])) ? $_REQUEST['lm_arrears_fee'] : '0';
		$lm_total_fee = (!empty($_REQUEST['lm_total_fee'])) ? $_REQUEST['lm_total_fee'] : '0';

		$checkBillExist = $this->BillModel->checkBillExist($lm_building_seq, $lm_room_num, $lm_bill_year, $lm_bill_month);

		$get_tenant_info = $this->BillModel->getTenantInfo($lm_building_seq, $lm_room_num);


		$send_arr = array(
			'lm_user_seq' => $userSeq,
			'lm_building_seq' => $lm_building_seq,
			'lm_room_num' => $lm_room_num,
			'lm_bill_year' => $lm_bill_year,
			'lm_bill_month' => $lm_bill_month,
			'lm_rental_fee' => $lm_rental_fee,
			'lm_maintenanace_fee' => $lm_maintenanace_fee,
			'lm_etc_fee' => $lm_etc_fee,
			'lm_electro_fee' => $lm_electro_fee,
			'lm_water_fee' => $lm_water_fee,
			'lm_gas_fee' => $lm_gas_fee,
			'lm_etc_cost' => $lm_etc_cost,
			'lm_arrears_fee' => $lm_arrears_fee,
			'lm_total_fee' => $lm_total_fee,
			'lm_tenant_seq' => $get_tenant_info[0]['seq']
		);

		try{
			if(!empty($checkBillExist[0])){
				//있으면 update
				$this->BillModel->modBillInfo($send_arr);

			}else{
				//없으면 insert
				$this->BillModel->insertBillInfo($send_arr);

			}

			$return_arr = array(
				'result' => true,
				'msg' => '정상적으로 반영 되었습니다.'
			);
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '오류가 발생하였습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);


		echo $return_arr;
	}

	public function sendTotalSMS(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$thisy = date("Y", time());
		$thism = date("m", time());

		$billTotalInfo = $this->BillModel->getBillInfoTotal($userSeq, $thisy, $thism);
		try{
			foreach($billTotalInfo as $key => $val){
				$building_seq = $val['lm_building_seq'];
				$lm_room_num = $val['lm_room_num'];
				$lm_charge_year = $thisy;
				$lm_charge_month = $thism;

				$billIndiInfo = $this->BillModel->getBillInfoIndimod($building_seq, $lm_room_num, $lm_charge_year, $lm_charge_month);


				$sID = "ncp:sms:kr:273086673029:imdaebang_sms"; // 서비스 ID
				$smsURL = "https://sens.apigw.ntruss.com/sms/v2/services/".$sID."/messages";
				$smsUri = "/sms/v2/services/".$sID."/messages";
				$sKey = "{c9ab567ac35444bb873e844003de2da0}";

				$accKeyId = "YLW8X0LZ4h1Mgkxzawnr";
				$accSecKey = "a9D96hACUmqik4o1kWe373C2HmpUQmIhZhvEe7rB";

				$sTime = floor(microtime(true) * 1000);

				$billInfo = $billIndiInfo[0];
				$bill_start_date = "";
				$bill_end_date = "";
				$bill_claim_ym = $billIndiInfo[0]['lm_bill_year']."-".$billIndiInfo[0]['lm_bill_month'];
				$bill_claim_ym = date('Y-m', strtotime($bill_claim_ym));

				$bill_start_date = date("Y-m-01", strtotime($bill_claim_ym." -1 month"));
				$bill_end_date = date("Y-m-t", strtotime($bill_claim_ym." -1 month"));

				$content = "
[임대방] 
".$billInfo['lm_tenant_name']."님! ".$billInfo['lm_bill_year']."년 ".$billInfo['lm_bill_month']."월분 청구서 알려드립니다.
		
건물명 : ".$billInfo['building_name']."
호실 번호 : ".$billInfo['lm_room_num']."
청구서 귀속 기간 : ".$bill_start_date." ~ ".$bill_end_date."
임차료 : ".number_format($billInfo['lm_rental_fee'])." 원
관리비 : ".number_format($billInfo['lm_maintenanace_fee'])." 원
기타비 : ".number_format($billInfo['lm_etc_fee'])." 원 
전기요금 : ".number_format($billInfo['lm_electro_fee'])." 원
수도요금 : ".number_format($billInfo['lm_water_fee'])." 원 
가스요금 : ".number_format($billInfo['lm_gas_fee'])." 원 
기타요금 : ".number_format($billInfo['lm_etc_cost'])." 원 
전월까지의 체납액 : ".number_format($billInfo['lm_arrears_fee'])." 원 

청구 합계액 : ".number_format($billInfo['lm_total_fee'])." 원

이상입니다.
		";

				$postData = array(
					'type' => 'LMS',
					'countryCode' => '82',
					'from' => '01082346334', // 발신번호 (등록되어있어야함)
					'contentType' => 'COMM',
					'content' => "테스트 메세지 내용",
					'messages' => array(array('content' => $content, 'to' => $billInfo['lm_tenant_phone']))
				);

				$postFields = json_encode($postData) ;

				$hashString = "POST {$smsUri}\n{$sTime}\n{$accKeyId}";
				$dHash = base64_encode( hash_hmac('sha256', $hashString, $accSecKey, true) );

				$header = array(
					// "accept: application/json",
					'Content-Type: application/json; charset=utf-8',
					'x-ncp-apigw-timestamp: '.$sTime,
					"x-ncp-iam-access-key: ".$accKeyId,
					"x-ncp-apigw-signature-v2: ".$dHash
				);

				// Setup cURL
				$ch = curl_init($smsURL);
				curl_setopt_array($ch, array(
					CURLOPT_POST => TRUE,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_POSTFIELDS => $postFields
				));

				curl_exec($ch);
			}
			$return_arr = array(
				'status' => true,
				'msg' => '정상적으로 발송하였습니다.'
			);
		}catch(Exception $e){
			$return_arr = array(
				'status' => false,
				'msg' => 'SMS 발송에 실패했습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function sendBillSMS(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_room_num =  (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$lm_charge_year =  (!empty($_REQUEST['charge_year'])) ? $_REQUEST['charge_year'] : '';
		$lm_charge_month =  (!empty($_REQUEST['charge_month'])) ? $_REQUEST['charge_month'] : '';

		$billIndiInfo = $this->BillModel->getBillInfoIndimod($building_seq, $lm_room_num, $lm_charge_year, $lm_charge_month);

		if(empty($billIndiInfo[0])){
			$return_arr = array(
				'msg' => '발송 가능한 청구서 데이터가 없습니다.',
				'result' => false
			);

			$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

			echo $return_arr;
		}else{

			try{
				$sID = "ncp:sms:kr:273086673029:imdaebang_sms"; // 서비스 ID
				$smsURL = "https://sens.apigw.ntruss.com/sms/v2/services/".$sID."/messages";
				$smsUri = "/sms/v2/services/".$sID."/messages";
				$sKey = "{c9ab567ac35444bb873e844003de2da0}";

				$accKeyId = "YLW8X0LZ4h1Mgkxzawnr";
				$accSecKey = "a9D96hACUmqik4o1kWe373C2HmpUQmIhZhvEe7rB";

				$sTime = floor(microtime(true) * 1000);

				$billInfo = $billIndiInfo[0];
				$bill_start_date = "";
				$bill_end_date = "";
				$bill_claim_ym = $billIndiInfo[0]['lm_bill_year']."-".$billIndiInfo[0]['lm_bill_month'];
				$bill_claim_ym = date('Y-m', strtotime($bill_claim_ym));

				$bill_start_date = date("Y-m-01", strtotime($bill_claim_ym." -1 month"));
				$bill_end_date = date("Y-m-t", strtotime($bill_claim_ym." -1 month"));

				$content = "
[임대방] 
".$billInfo['lm_tenant_name']."님! ".$billInfo['lm_bill_year']."년 ".$billInfo['lm_bill_month']."월분 청구서 알려드립니다.
		
건물명 : ".$billInfo['building_name']."
호실 번호 : ".$billInfo['lm_room_num']."
청구서 귀속 기간 : ".$bill_start_date." ~ ".$bill_end_date."
임차료 : ".number_format($billInfo['lm_rental_fee'])." 원
관리비 : ".number_format($billInfo['lm_maintenanace_fee'])." 원
기타비 : ".number_format($billInfo['lm_etc_fee'])." 원 
전기요금 : ".number_format($billInfo['lm_electro_fee'])." 원
수도요금 : ".number_format($billInfo['lm_water_fee'])." 원 
가스요금 : ".number_format($billInfo['lm_gas_fee'])." 원 
기타요금 : ".number_format($billInfo['lm_etc_cost'])." 원 
전월까지의 체납액 : ".number_format($billInfo['lm_arrears_fee'])." 원 

청구 합계액 : ".number_format($billInfo['lm_total_fee'])." 원

이상입니다.
		";

				$postData = array(
					'type' => 'LMS',
					'countryCode' => '82',
					'from' => '01082346334', // 발신번호 (등록되어있어야함)
					'contentType' => 'COMM',
					'content' => "테스트 메세지 내용",
					'messages' => array(array('content' => $content, 'to' => $billInfo['lm_tenant_phone']))
				);

				$postFields = json_encode($postData) ;

				$hashString = "POST {$smsUri}\n{$sTime}\n{$accKeyId}";
				$dHash = base64_encode( hash_hmac('sha256', $hashString, $accSecKey, true) );

				$header = array(
					// "accept: application/json",
					'Content-Type: application/json; charset=utf-8',
					'x-ncp-apigw-timestamp: '.$sTime,
					"x-ncp-iam-access-key: ".$accKeyId,
					"x-ncp-apigw-signature-v2: ".$dHash
				);

				// Setup cURL
				$ch = curl_init($smsURL);
				curl_setopt_array($ch, array(
					CURLOPT_POST => TRUE,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_POSTFIELDS => $postFields
				));

				curl_exec($ch);

				$return_arr = array(
					'status' => true,
					'msg' => '정상적으로 발송하였습니다.'
				);
			}catch(Exception $e){
				$return_arr = array(
					'status' => false,
					'msg' => 'SMS 발송에 실패했습니다.'
				);
			}

			$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

			echo $return_arr;
		}
	}
}
