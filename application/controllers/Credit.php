<?php


class Credit extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('CreditModel', 'BillModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/credit/index');
	}

	public function getCreditList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$claim_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '';
		$claim_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		$send_data = array(
			'income_year'=>$claim_year,
			'income_month'=>$claim_month,
			'room_num' => $room_num,
			'building_seq' => $building_seq
		);

		$creditListInfo = $this->CreditModel->getCreditList($send_data);


		$return_arr = json_encode($creditListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getCreditInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$credit_year = (!empty($_REQUEST['credit_year'])) ? $_REQUEST['credit_year'] : '';
		$credit_month = (!empty($_REQUEST['credit_month'])) ? $_REQUEST['credit_month'] : '';

		$send_data = array(
			'building_name'=>$building_name,
			'room_num'=>$room_num,
			'credit_year' => $credit_year,
			'credit_month' => $credit_month
		);

		$creditIndiInfo = $this->CreditModel->getCreditListIndi($send_data);
		$return_arr = json_encode($creditIndiInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function modCreditInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$lm_building_seq = (!empty($_REQUEST['lm_building_seq'])) ? $_REQUEST['lm_building_seq'] : '';
		$lm_room_num = (!empty($_REQUEST['lm_room_num'])) ? $_REQUEST['lm_room_num'] : '';

		$credit_ym = (!empty($_REQUEST['credit_ym'])) ? $_REQUEST['credit_ym'] : '';
		$credit_ym = explode("-", $credit_ym);

		$credit_year = $credit_ym[0];
		$credit_month = $credit_ym[1];

		$first_income = (!empty($_REQUEST['first_income'])) ? $_REQUEST['first_income'] : '0';
		$second_income = (!empty($_REQUEST['second_income'])) ? $_REQUEST['second_income'] : '0';
		$third_income = (!empty($_REQUEST['third_income'])) ? $_REQUEST['third_income'] : '0';
		$total_income = $first_income + $second_income + $third_income;

		$checkBillExist = $this->CreditModel->checkCreditExist($lm_building_seq, $lm_room_num, $credit_year, $credit_month);

		$get_tenant_info = $this->BillModel->getTenantInfo($lm_building_seq, $lm_room_num);


		$send_arr = array(
			'lm_user_seq' => $userSeq,
			'lm_building_seq' => $lm_building_seq,
			'lm_room_num' => $lm_room_num,
			'income_year' => $credit_year,
			'income_month' => $credit_month,
			'first_income' => $first_income,
			'second_income' => $second_income,
			'third_income' => $third_income,
			'total_income' => $total_income,
			'lm_tenant_seq' => $get_tenant_info[0]['seq']
		);

		try{
			if(!empty($checkBillExist[0])){
				//있으면 update
				$this->CreditModel->modCreditInfo($send_arr);

			}else{
				//없으면 insert
				$this->CreditModel->insertCreditInfo($send_arr);

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
}
