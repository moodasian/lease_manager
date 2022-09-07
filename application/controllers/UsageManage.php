<?php


class UsageManage extends CI_Controller
{
	/*
	 * 건물관리 Controller (건물 정보 등록, 수정, 삭제)
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('UsageManageModel', 'UserJoinModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/usagemanage/index');
	}

	public function getUsageManageList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$fee_gubun = (!empty($_REQUEST['fee_gubun'])) ? $_REQUEST['fee_gubun'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$lm_ym = (!empty($_REQUEST['lm_ym'])) ? $_REQUEST['lm_ym'] : '';

		if(empty($lm_ym)){
			$claim_year = "";
			$claim_month = "";
		}else{
			$lm_str = explode("-", $lm_ym);
			$claim_year = $lm_str[0];
			$claim_month = $lm_str[1];
		}


		$send_data = array(
			'building_seq'=>$building_seq,
			'fee_gubun'=>$fee_gubun,
			'claim_year'=>$claim_year,
			'claim_month'=>$claim_month,
			'room_num'=>$room_num
		);


		$leaseListInfo = $this->UsageManageModel->getUsageManageList($send_data);

		$return_arr = json_encode($leaseListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getPrevClaimSelect(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$prevClaimInfo = $this->UsageManageModel->getPrevClaimInfo($userSeq);

		$return_arr = array();
		foreach($prevClaimInfo as $key => $val){
			$claim_ym = $val['lm_charge_year']."-".$val['lm_charge_month'];
			array_push($return_arr, $claim_ym);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getPrevClaimList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$prev_y = (!empty($_REQUEST['prev_y'])) ? $_REQUEST['prev_y'] : '';
		$prev_m = (!empty($_REQUEST['prev_m'])) ? $_REQUEST['prev_m'] : '';
		$fee_gubun = (!empty($_REQUEST['fee_gubun'])) ? $_REQUEST['fee_gubun'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';

		$send_data = array(
			'user_seq'=>$userSeq,
			'claim_year'=>$prev_y,
			'claim_month'=>$prev_m,
			'fee_gubun' => $fee_gubun,
			'building_seq' => $building_seq
		);

		$prevClaimList = $this->UsageManageModel->getPrevClaimList($send_data);

		$return_arr = json_encode($prevClaimList, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getThisClaimList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$this_y = (!empty($_REQUEST['this_y'])) ? $_REQUEST['this_y'] : '';
		$this_m = (!empty($_REQUEST['this_m'])) ? $_REQUEST['this_m'] : '';
		$fee_gubun = (!empty($_REQUEST['fee_gubun'])) ? $_REQUEST['fee_gubun'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';

		$send_data = array(
			'user_seq'=>$userSeq,
			'claim_year'=>$this_y,
			'claim_month'=>$this_m,
			'fee_gubun' => $fee_gubun,
			'building_seq' => $building_seq
		);

		$prevClaimList = $this->UsageManageModel->getPrevClaimList($send_data);

		$return_arr = json_encode($prevClaimList, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function modUsageManageInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$claim_year = (!empty($_REQUEST['claim_year'])) ? $_REQUEST['claim_year'] : '';
		$claim_month = (!empty($_REQUEST['claim_month'])) ? $_REQUEST['claim_month'] : '';
		$usage_meter = (!empty($_REQUEST['usage_meter'])) ? $_REQUEST['usage_meter'] : '';
		$usage_meter_prev = (!empty($_REQUEST['usage_meter_prev'])) ? $_REQUEST['usage_meter_prev'] : '';
		$usage_gubun = (!empty($_REQUEST['usage_gubun'])) ? $_REQUEST['usage_gubun'] : '';
		$usage_amount = (!empty($_REQUEST['usage_amount'])) ? $_REQUEST['usage_amount'] : '';
		$usage_cost = (!empty($_REQUEST['usage_cost'])) ? $_REQUEST['usage_cost'] : '';

		$claim_year_prev = (!empty($_REQUEST['claim_year_prev'])) ? $_REQUEST['claim_year_prev'] : '';
		$claim_month_prev = (!empty($_REQUEST['claim_month_prev'])) ? $_REQUEST['claim_month_prev'] : '';

		$claim_ym = $claim_year."-".$claim_month;
		$claim_ym_prev = $claim_year_prev."-".$claim_month_prev;

		//귀속년 이전달
		$prev_prev_ym = date("Y-m", strtotime($claim_ym_prev." -1 month"));
		$prev_prev_str = explode("-", $prev_prev_ym);
		$prev_prev_year = $prev_prev_str[0];
		$prev_prev_month = $prev_prev_str[1];



		try{
			if($usage_gubun == '전기'){
				$usage_gubun = 'A';
			}elseif($usage_gubun == '가스'){
				$usage_gubun = 'B';
			}elseif($usage_gubun == '수도'){
				$usage_gubun = 'C';
			}elseif($usage_gubun == '기타'){
				$usage_gubun = 'D';
			}

			$send_arr = array(
				'lm_building_seq' => $building_seq,
				'lm_room_num' => $room_num,
				'lm_usage_gubun' => $usage_gubun,
				'lm_usage_meter' => $usage_meter,
				'lm_usage_amount' => $usage_amount,
				'lm_usage_cost' => $usage_cost,
				'lm_charge_year' => $claim_year,
				'lm_charge_month' => $claim_month
			);

			$this->UsageManageModel->modUsageManageInfo($send_arr);


			
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

	public function getUsageManageInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$seq = (!empty($_REQUEST['seq'])) ? $_REQUEST['seq'] : '';

		$usageManageInfoIndi = $this->UsageManageModel->getUsageManageInfoIndi($seq);

		$return = json_encode($usageManageInfoIndi[0], JSON_UNESCAPED_UNICODE);

		echo $return;

	}

	public function modUsageManageIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$seq = (!empty($_REQUEST['seq'])) ? $_REQUEST['seq'] : '';
		$lm_usage_amount = (!empty($_REQUEST['lm_usage_amount'])) ? $_REQUEST['lm_usage_amount'] : '0';
		$lm_usage_meter = (!empty($_REQUEST['lm_usage_meter'])) ? $_REQUEST['lm_usage_meter'] : '0';
		$lm_usage_cost = (!empty($_REQUEST['lm_usage_cost'])) ? $_REQUEST['lm_usage_cost'] : '0';


		try{
			$send_arr = array(
				'seq' => $seq,
				'lm_usage_meter' => $lm_usage_meter,
				'lm_usage_amount' => $lm_usage_amount,
				'lm_usage_cost' => $lm_usage_cost
			);

			$this->UsageManageModel->modUsageManageInfoIndi($send_arr);

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
}
