<?php


class BasicFee extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BasicFeeModel', 'UserJoinModel'));
	}

	public function index(){
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}
		$this->load->view('/basicfee/index');
	}

	public function getBasicFeeList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$fee_gubun = (!empty($_REQUEST['fee_gubun'])) ? $_REQUEST['fee_gubun'] : '';
		$lm_ym = (!empty($_REQUEST['lm_ym'])) ? $_REQUEST['lm_ym'] : '';
		if(!empty($lm_ym)){
			$lm_str = explode("-", $lm_ym);
			$lm_year = $lm_str[0];
			$lm_month = $lm_str[1];
		}else{
			$lm_year = date("Y", time());
			$lm_month = date("m", time());
		}

		//lm_room_info를 기준으로 lm_room_contract_info, lm_tenant_info가 비었을 경우 left join으로 빈 값 가져옴
		$leaseListInfo = $this->BasicFeeModel->getBasicFeeList($userSeq, $building_name, $fee_gubun, $lm_year, $lm_month);

		$return_arr = json_encode($leaseListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getBasicFeeIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$basicfee_seq = (!empty($_REQUEST['basicfee_seq'])) ? $_REQUEST['basicfee_seq'] : '';


		$basicFeeInfo = $this->BasicFeeModel->getBasicFeeInfoIndi($basicfee_seq, $building_seq);

		if(empty($basicFeeInfo)){
			$basicFeeInfo[0] = array();
		}

		$return_arr = json_encode($basicFeeInfo[0], JSON_UNESCAPED_UNICODE);


		echo $return_arr;
	}

	public function modBasicFeeInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$charge_fee_before = (!empty($_REQUEST['charge_fee_before'])) ? $_REQUEST['charge_fee_before'] : '0';
		$deduct_fee = (!empty($_REQUEST['deduct_fee'])) ? $_REQUEST['deduct_fee'] : '0';
		$deduct_reason = (!empty($_REQUEST['deduct_reason'])) ? $_REQUEST['deduct_reason'] : '';
		$charge_fee_after = (!empty($_REQUEST['charge_fee_after'])) ? $_REQUEST['charge_fee_after'] : '0';
		$use_amount = (!empty($_REQUEST['use_amount'])) ? $_REQUEST['use_amount'] : '0';
		$use_basicfee_per = (!empty($_REQUEST['use_basicfee_per'])) ? $_REQUEST['use_basicfee_per'] : '0';
		$use_start_date = (!empty($_REQUEST['use_start_date'])) ? $_REQUEST['use_start_date'] : null;
		$use_end_date = (!empty($_REQUEST['use_end_date'])) ? $_REQUEST['use_end_date'] : null;

		$basicfee_seq = (!empty($_REQUEST['basicfee_seq'])) ? $_REQUEST['basicfee_seq'] : null;

		try{
			$basicfee_info_arr = array(
				'lm_user_seq' => $userSeq,
				'building_name' => $building_name,
				'charge_fee_before' => $charge_fee_before,
				'deduct_fee' => $deduct_fee,
				'deduct_reason' => $deduct_reason,
				'charge_fee_after' => $charge_fee_after,
				'use_amount' => $use_amount,
				'use_basicfee_per' => $use_basicfee_per,
				'basicfee_seq' => $basicfee_seq
			);

			$this->BasicFeeModel->modBasicFeeInfo($basicfee_info_arr);

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

	public function sendBillMail(){

	}

	public function getFacilityCount(){

	}
}
