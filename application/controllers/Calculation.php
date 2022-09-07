<?php


class Calculation extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('CalculationModel', 'UserJoinModel'));
	}

	public function index(){
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}
		$this->load->view('/calculation/index');
	}

	public function getCalculationList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$calculation_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '';
		$calculation_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '';

		$send_data = array(
			'building_seq'=> $building_seq,
			'building_name'=>$building_name,
			'lm_calculation_year' => $calculation_year,
			'lm_calculation_month' => $calculation_month
		);

		$calculationListInfo = $this->CalculationModel->getCalculationList($send_data);

		$return_arr = json_encode($calculationListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getCalculationIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '7';
		$calculation_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '2022';
		$calculation_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '08';

		$send_data = array(
			'building_seq'=> $building_seq,
			'lm_calculation_year' => $calculation_year,
			'lm_calculation_month' => $calculation_month,
			'lm_year' => $calculation_year,
			'lm_month' => $calculation_month
		);

		$calculationListInfo = $this->CalculationModel->getCalculationIndi($send_data);

		$firstYN = $calculationListInfo[0]['first_yn'];

		if($firstYN == 'N'){
			$calculationListInfo = $this->CalculationModel->getCalculationIndifrom($send_data);
			$return_arr = json_encode($calculationListInfo[0], JSON_UNESCAPED_UNICODE);

			echo $return_arr;
		}else{
			$return_arr = json_encode($calculationListInfo[0], JSON_UNESCAPED_UNICODE);

			echo $return_arr;
		}
	}

	public function modCalculationInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '7';
		$calculation_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '2022';
		$calculation_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '08';

		$lm_total_rental_fee = (!empty($_REQUEST['lm_total_rental_fee'])) ? $_REQUEST['lm_total_rental_fee'] : '0';
		$lm_total_management_fee = (!empty($_REQUEST['lm_total_management_fee'])) ? $_REQUEST['lm_total_management_fee'] : '0';
		$lm_total_etc_fee = (!empty($_REQUEST['lm_total_etc_fee'])) ? $_REQUEST['lm_total_etc_fee'] : '0';
		$lm_total_expense = (!empty($_REQUEST['lm_total_expense'])) ? $_REQUEST['lm_total_expense'] : '0';
		$lm_total_electro_income = (!empty($_REQUEST['lm_total_electro_income'])) ? $_REQUEST['lm_total_electro_income'] : '0';
		$lm_total_water_income = (!empty($_REQUEST['lm_total_water_income'])) ? $_REQUEST['lm_total_water_income'] : '0';
		$lm_total_gas_income = (!empty($_REQUEST['lm_total_gas_income'])) ? $_REQUEST['lm_total_gas_income'] : '0';
		$lm_total_etc_income = (!empty($_REQUEST['lm_total_etc_income'])) ? $_REQUEST['lm_total_etc_income'] : '0';
		$lm_total_income = (!empty($_REQUEST['lm_total_income'])) ? $_REQUEST['lm_total_income'] : '0';

		$send_data = array(
			'building_seq'=>$building_seq,
			'lm_calculation_year'=>$calculation_year,
			'lm_calculation_month'=>$calculation_month,
			'lm_total_rental_fee'=>$lm_total_rental_fee,
			'lm_total_management_fee'=>$lm_total_management_fee,
			'lm_total_etc_fee'=>$lm_total_etc_fee,
			'lm_total_expense'=>$lm_total_expense,
			'lm_total_electro_income'=>$lm_total_electro_income,
			'lm_total_water_income'=>$lm_total_water_income,
			'lm_total_gas_income'=>$lm_total_gas_income,
			'lm_total_etc_income'=>$lm_total_etc_income,
			'lm_total_income'=>$lm_total_income
		);

		try{

			$this->CalculationModel->modCalculationInfo($send_data);

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

	public function delCalculationInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '7';
		$calculation_year = (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '2022';
		$calculation_month = (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '08';

		$send_data = array(
			'building_seq'=>$building_seq,
			'lm_calculation_year'=>$calculation_year,
			'lm_calculation_month'=>$calculation_month
		);

		try{

			$this->CalculationModel->delCalculationInfo($send_data);

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
