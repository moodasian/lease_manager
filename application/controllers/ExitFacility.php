<?php


class ExitFacility extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('ExitFacilityModel', 'UserJoinModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/exitfacility/index');
	}

	public function sendExitFacilitySMS(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_room_num =  (!empty($_REQUEST['lm_room_num'])) ? $_REQUEST['lm_room_num'] : '';
		$lm_room_deposit =  (!empty($_REQUEST['lm_room_deposit'])) ? $_REQUEST['lm_room_deposit'] : '0';
		$lm_monthly_fee = (!empty($_REQUEST['lm_monthly_fee'])) ? $_REQUEST['lm_monthly_fee'] : '0';
		$lm_management_fee =  (!empty($_REQUEST['lm_management_fee'])) ? $_REQUEST['lm_management_fee'] : '0';
		$lm_etc_fee =  (!empty($_REQUEST['lm_etc_fee'])) ? $_REQUEST['lm_etc_fee'] : '0';
		$lm_electro_cost =  (!empty($_REQUEST['lm_electro_cost'])) ? $_REQUEST['lm_electro_cost'] : '0';
		$lm_water_cost =  (!empty($_REQUEST['lm_water_cost'])) ? $_REQUEST['lm_water_cost'] : '0';
		$lm_gas_cost =  (!empty($_REQUEST['lm_gas_cost'])) ? $_REQUEST['lm_gas_cost'] : '0';
		$lm_etc_cost =  (!empty($_REQUEST['lm_etc_cost'])) ? $_REQUEST['lm_etc_cost'] : '0';
		$lm_brokerage_fee =  (!empty($_REQUEST['lm_brokerage_fee'])) ? $_REQUEST['lm_brokerage_fee'] : '0';
		$lm_total_arrears = (!empty($_REQUEST['lm_total_arrears'])) ? $_REQUEST['lm_total_arrears'] : '0';
		$lm_exit_cleaning_cost =  (!empty($_REQUEST['lm_exit_cleaning_cost'])) ? $_REQUEST['lm_exit_cleaning_cost'] : '0';
		$lm_total_cost =  (!empty($_REQUEST['lm_total_cost'])) ? $_REQUEST['lm_total_cost'] : '0';

		$tenantInfo = $this->ExitFacilityModel->getExitFacilityTenantInfo($building_seq, $lm_room_num);


		try{
			$sID = "ncp:sms:kr:273086673029:imdaebang_sms"; // 서비스 ID
			$smsURL = "https://sens.apigw.ntruss.com/sms/v2/services/".$sID."/messages";
			$smsUri = "/sms/v2/services/".$sID."/messages";
			$sKey = "{c9ab567ac35444bb873e844003de2da0}";

			$accKeyId = "YLW8X0LZ4h1Mgkxzawnr";
			$accSecKey = "a9D96hACUmqik4o1kWe373C2HmpUQmIhZhvEe7rB";

			$sTime = floor(microtime(true) * 1000);
			$tenantInfo = $tenantInfo[0];

			$content = "
[임대방] 
".$tenantInfo['lm_tenant_name']."님! ".$tenantInfo['building_name']." / ".$tenantInfo['lm_room_num']."호의 퇴실 정산 내역을 알려드립니다.
		
건물명 : ".$tenantInfo['building_name']."
호실 번호 : ".$tenantInfo['lm_room_num']."
보증금 : ".number_format($lm_room_deposit)." 원
월 임대료 : ".number_format($lm_monthly_fee)." 원
관리비 : ".number_format($lm_management_fee)." 원 
기타비 : ".number_format($lm_etc_fee)." 원 
전기요금 : ".number_format($lm_electro_cost)." 원 
수도요금 : ".number_format($lm_water_cost)." 원 
가스요금 : ".number_format($lm_gas_cost)." 원 
기타요금 : ".number_format($lm_etc_cost)." 원 
체납액 : ".number_format($lm_total_arrears)." 원 
즁개수수료 : ".number_format($lm_brokerage_fee)." 원 
퇴실청소비 : ".number_format($lm_exit_cleaning_cost)." 원 

청구 합계액 : ".number_format($lm_total_cost)." 원

이상입니다.";

			$postData = array(
				'type' => 'LMS',
				'countryCode' => '82',
				'from' => '01082346334', // 발신번호 (등록되어있어야함)
				'contentType' => 'COMM',
				'content' => "테스트 메세지 내용",
				'messages' => array(array('content' => $content, 'to' => $tenantInfo['lm_tenant_phone']))
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

	public function insertExitFacilityDoc(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];


		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_room_num =  (!empty($_REQUEST['lm_room_num'])) ? $_REQUEST['lm_room_num'] : '';
		$lm_room_deposit =  (!empty($_REQUEST['lm_room_deposit'])) ? $_REQUEST['lm_room_deposit'] : '0';
		$lm_monthly_fee =  (!empty($_REQUEST['lm_monthly_fee'])) ? $_REQUEST['lm_monthly_fee'] : '0';
		$lm_management_fee =  (!empty($_REQUEST['lm_management_fee'])) ? $_REQUEST['lm_management_fee'] : '0';
		$lm_etc_fee =  (!empty($_REQUEST['lm_etc_fee'])) ? $_REQUEST['lm_etc_fee'] : '0';
		$lm_electro_cost =  (!empty($_REQUEST['lm_electro_cost'])) ? $_REQUEST['lm_electro_cost'] : '0';
		$lm_water_cost =  (!empty($_REQUEST['lm_water_cost'])) ? $_REQUEST['lm_water_cost'] : '0';
		$lm_gas_cost =  (!empty($_REQUEST['lm_gas_cost'])) ? $_REQUEST['lm_gas_cost'] : '0';
		$lm_etc_cost =  (!empty($_REQUEST['lm_etc_cost'])) ? $_REQUEST['lm_etc_cost'] : '0';
		$lm_brokerage_fee =  (!empty($_REQUEST['lm_brokerage_fee'])) ? $_REQUEST['lm_brokerage_fee'] : '0';
		$lm_deposit_pm =  (!empty($_REQUEST['lm_deposit_pm'])) ? $_REQUEST['lm_deposit_pm'] : '0';
		$lm_monthly_fee_unpaid =  (!empty($_REQUEST['lm_monthly_fee_unpaid'])) ? $_REQUEST['lm_monthly_fee_unpaid'] : '0';
		$lm_management_fee_unpaid =  (!empty($_REQUEST['lm_management_fee_unpaid'])) ? $_REQUEST['lm_management_fee_unpaid'] : '0';
		$lm_etc_fee_unpaid =  (!empty($_REQUEST['lm_etc_fee_unpaid'])) ? $_REQUEST['lm_etc_fee_unpaid'] : '0';
		$lm_electro_cost_unpaid =  (!empty($_REQUEST['lm_electro_cost_unpaid'])) ? $_REQUEST['lm_electro_cost_unpaid'] : '0';
		$lm_water_cost_unpaid =  (!empty($_REQUEST['lm_water_cost_unpaid'])) ? $_REQUEST['lm_water_cost_unpaid'] : '0';
		$lm_gas_cost_unpaid =  (!empty($_REQUEST['lm_gas_cost_unpaid'])) ? $_REQUEST['lm_gas_cost_unpaid'] : '0';
		$lm_etc_cost_unpaid =  (!empty($_REQUEST['lm_etc_cost_unpaid'])) ? $_REQUEST['lm_etc_cost_unpaid'] : '0';
		$lm_exit_cleaning_cost =  (!empty($_REQUEST['lm_exit_cleaning_cost'])) ? $_REQUEST['lm_exit_cleaning_cost'] : '0';
		$lm_total_cost =  (!empty($_REQUEST['lm_total_cost'])) ? $_REQUEST['lm_total_cost'] : '0';
		$lm_exit_ymd = date("Y-m-d", time());
		$lm_exit_ym = date("Ym", time());



		$tenantInfo = $this->ExitFacilityModel->getExitFacilityTenantInfo($building_seq, $lm_room_num);
		$tenantInfo = $tenantInfo[0];
		$tenant_seq = $tenantInfo['seq'];

		$send_arr = array(
			'lm_user_seq' => $userSeq,
			'lm_building_seq' => $building_seq,
			'lm_room_num' => $lm_room_num,
			'lm_room_deposit' => $lm_room_deposit,
			'lm_management_fee' => $lm_management_fee,
			'lm_etc_fee' => $lm_etc_fee,
			'lm_electro_cost' => $lm_electro_cost,
			'lm_water_cost' => $lm_water_cost,
			'lm_gas_cost' => $lm_gas_cost,
			'lm_etc_cost' => $lm_etc_cost,
			'lm_brokerage_fee' => $lm_brokerage_fee,
			'lm_deposit_pm' => $lm_deposit_pm,
			'lm_monthly_fee_unpaid' => $lm_monthly_fee_unpaid,
			'lm_management_fee_unpaid' => $lm_management_fee_unpaid,
			'lm_etc_fee_unpaid' => $lm_etc_fee_unpaid,
			'lm_electro_cost_unpaid' => $lm_electro_cost_unpaid,
			'lm_water_cost_unpaid' => $lm_water_cost_unpaid,
			'lm_gas_cost_unpaid' => $lm_gas_cost_unpaid,
			'lm_etc_cost_unpaid' => $lm_etc_cost_unpaid,
			'lm_exit_cleaning_cost' => $lm_exit_cleaning_cost,
			'lm_total_cost' => $lm_total_cost,
			'lm_exit_ymd' => $lm_exit_ymd,
			'lm_exit_ym' => $lm_exit_ym,
			'lm_tenant_seq' => $tenant_seq,
			'lm_adjustment_ym' => $lm_exit_ym,
			'lm_monthly_fee' => $lm_monthly_fee
		);

		try{
			$this->ExitFacilityModel->insertExitFacilityDocs($send_arr);

			$return_arr = array(
				'status' => true,
				'msg' => '정상적으로 저장하였습니다.'
			);

		}catch(Exception $e){
			$return_arr = array(
				'status' => false,
				'msg' => '퇴실정산서 저장에 실패했습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

}
