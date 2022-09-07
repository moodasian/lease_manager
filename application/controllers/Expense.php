<?php


class Expense extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('ExpenseModel', 'UserJoinModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/expense/index');
	}

	public function getExpenseList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$expense_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '';
		$expense_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '';

		$send_data = array(
			'building_seq'=> $building_seq,
			'lm_expense_year' => $expense_year,
			'lm_expense_month' => $expense_month
		);

		$creditListInfo = $this->ExpenseModel->getExpenseList($send_data);


		$return_arr = json_encode($creditListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getExpenseInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '7';
		$lm_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '2022';
		$lm_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '08';

		$expenseIndiInfo = $this->ExpenseModel->getExpenseInfoIndi($building_seq, $lm_year, $lm_month);

		$return_arr = json_encode($expenseIndiInfo[0], JSON_UNESCAPED_UNICODE);


		echo $return_arr;

	}

	public function modExpenseInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : null;
		$lm_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : null;

		$lm_manager_pay = (!empty($_REQUEST['lm_manager_pay'])) ? $_REQUEST['lm_manager_pay'] : '';
		$lm_cleaner_pay = (!empty($_REQUEST['lm_cleaner_pay'])) ? $_REQUEST['lm_cleaner_pay'] : '0';
		$lm_etc_pay = (!empty($_REQUEST['lm_etc_pay'])) ? $_REQUEST['lm_etc_pay'] : '0';
		$lm_maintence_bill = (!empty($_REQUEST['lm_maintence_bill'])) ? $_REQUEST['lm_maintence_bill'] : '0';
		$lm_maintence_card = (!empty($_REQUEST['lm_maintence_card'])) ? $_REQUEST['lm_maintence_card'] : '0';

		$lm_fire_management_fee = (!empty($_REQUEST['lm_fire_management_fee'])) ? $_REQUEST['lm_fire_management_fee'] : '0';
		$lm_electro_manage_fee = (!empty($_REQUEST['lm_electro_manage_fee'])) ? $_REQUEST['lm_electro_manage_fee'] : '0';
		$lm_ev_fee = (!empty($_REQUEST['lm_ev_fee'])) ? $_REQUEST['lm_ev_fee'] : '0';
		$lm_secure_fee = (!empty($_REQUEST['lm_secure_fee'])) ? $_REQUEST['lm_secure_fee'] : '0';
		$lm_broadcast_fee = (!empty($_REQUEST['lm_broadcast_fee'])) ? $_REQUEST['lm_broadcast_fee'] : '0';
		$lm_etc_manage_fee = (!empty($_REQUEST['lm_etc_manage_fee'])) ? $_REQUEST['lm_etc_manage_fee'] : '0';

		$lm_electro_fee = (!empty($_REQUEST['lm_electro_fee'])) ? $_REQUEST['lm_electro_fee'] : '0';
		$lm_water_fee = (!empty($_REQUEST['lm_water_fee'])) ? $_REQUEST['lm_water_fee'] : '0';
		$lm_gas_fee = (!empty($_REQUEST['lm_gas_fee'])) ? $_REQUEST['lm_gas_fee'] : '0';
		$lm_etc_fee = (!empty($_REQUEST['lm_etc_fee'])) ? $_REQUEST['lm_etc_fee'] : '0';
		$lm_total_fee = (!empty($_REQUEST['lm_total_fee'])) ? $_REQUEST['lm_total_fee'] : '0';

		try{
			$basicfee_info_arr = array(
				'lm_user_seq' => $userSeq,
				'building_seq' => $building_seq,
				'lm_year' => $lm_year,
				'lm_month' => $lm_month,
				'lm_manager_pay' => $lm_manager_pay,
				'lm_cleaner_pay' => $lm_cleaner_pay,
				'lm_etc_pay' => $lm_etc_pay,
				'lm_maintence_bill' => $lm_maintence_bill,
				'lm_maintence_card' => $lm_maintence_card,
				'lm_fire_management_fee' => $lm_fire_management_fee,
				'lm_electro_manage_fee' => $lm_electro_manage_fee,
				'lm_ev_fee' => $lm_ev_fee,
				'lm_secure_fee' => $lm_secure_fee,
				'lm_broadcast_fee' => $lm_broadcast_fee,
				'lm_etc_manage_fee' => $lm_etc_manage_fee,
				'lm_electro_fee' => $lm_electro_fee,
				'lm_water_fee' => $lm_water_fee,
				'lm_gas_fee' => $lm_gas_fee,
				'lm_etc_fee' => $lm_etc_fee,
				'lm_total_fee' => $lm_total_fee
			);

			$this->ExpenseModel->modExpenseInfo($basicfee_info_arr);

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

	public function delExpenseInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$lm_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : null;
		$lm_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : null;



		try{
			$basicfee_info_arr = array(
				'lm_user_seq' => $userSeq,
				'building_seq' => $building_seq,
				'lm_year' => $lm_year,
				'lm_month' => $lm_month,
				'lm_manager_pay' => 0,
				'lm_cleaner_pay' => 0,
				'lm_etc_pay' => 0,
				'lm_maintence_bill' => 0,
				'lm_maintence_card' => 0,
				'lm_fire_management_fee' => 0,
				'lm_electro_manage_fee' => 0,
				'lm_ev_fee' => 0,
				'lm_secure_fee' => 0,
				'lm_broadcast_fee' => 0,
				'lm_etc_manage_fee' => 0,
				'lm_electro_fee' => 0,
				'lm_water_fee' => 0,
				'lm_gas_fee' => 0,
				'lm_etc_fee' => 0,
				'lm_total_fee' => 0
			);

			$this->ExpenseModel->modExpenseInfo($basicfee_info_arr);

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
