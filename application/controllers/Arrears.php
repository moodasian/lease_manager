<?php


class Arrears extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('ArrearsModel', 'UserJoinModel'));
	}

	public function index()
	{
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/arrears/index');
	}

	public function getArrearsList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$arrears_year = (!empty($_REQUEST['arrears_year'])) ? $_REQUEST['arrears_year'] : '';
		$arrears_month = (!empty($_REQUEST['arrears_month'])) ? $_REQUEST['arrears_month'] : '';
		$arrears_prev_month = "";
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';


		if(empty($arrears_year)){
			$arrears_year = date("Y", time());
		}
		if(empty($arrears_month)){
			$arrears_month = date("m", time());
		}
		$this_ym = date("Y-m", time());

		$arrears_prev_ym = date("Y-m", strtotime($this_ym." -1 month"));
		$arrears_prev_ym_str = explode("-", $arrears_prev_ym);
		$prev_year = $arrears_prev_ym_str[0];
		$prev_month = $arrears_prev_ym_str[1];

		$send_data = array(
			'building_seq'=> $building_seq,
			'building_name'=>$building_name,
			'arrears_year' => $arrears_year,
			'arrears_month' => $arrears_month,
			'arrears_prev_year' => $prev_year,
			'arrears_prev_month' => $prev_month,
			'room_num' => $room_num
		);

		$creditListInfo = $this->ArrearsModel->getArrearsList($send_data);



		$return_arr = json_encode($creditListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getArrearsInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$room_num =  (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$building_seq =  (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$arrears_year = (!empty($_REQUEST['arrears_year'])) ? $_REQUEST['arrears_year'] : '';
		$arrears_month = (!empty($_REQUEST['arrears_month'])) ? $_REQUEST['arrears_month'] : '';

		$arrears_ym = $arrears_year."-".$arrears_month;
		$this_ym = date("Y-m", strtotime($arrears_ym));

		$arrears_prev_ym = date("Y-m", strtotime($this_ym." -1 month"));
		$arrears_prev_ym_str = explode("-", $arrears_prev_ym);
		$prev_year = $arrears_prev_ym_str[0];
		$prev_month = $arrears_prev_ym_str[1];

		$send_data = array(
			'building_seq'=> $building_seq,
			'room_num'=> $room_num,
			'arrears_year' => $arrears_year,
			'arrears_month' => $arrears_month,
			'arrears_prev_year' => $prev_year,
			'arrears_prev_month' => $prev_month,
		);

		$indiInfo =  $this->ArrearsModel->getArrearsList($send_data);

		$return_arr = json_encode($indiInfo[0], JSON_UNESCAPED_UNICODE);

		echo $return_arr;

	}

	public function modArrearsInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$lm_room_num =  (!empty($_REQUEST['lm_room_num'])) ? $_REQUEST['lm_room_num'] : '';
		$lm_building_seq =  (!empty($_REQUEST['lm_building_seq'])) ? $_REQUEST['lm_building_seq'] : '';
		$lm_year =  (!empty($_REQUEST['lm_year'])) ? $_REQUEST['lm_year'] : '';
		$lm_month =  (!empty($_REQUEST['lm_month'])) ? $_REQUEST['lm_month'] : '';
		$total_claim_fee =  (!empty($_REQUEST['total_claim_fee'])) ? $_REQUEST['total_claim_fee'] : '';
		$total_income =  (!empty($_REQUEST['total_income'])) ? $_REQUEST['total_income'] : '';
		$this_arrears =  (!empty($_REQUEST['this_arrears'])) ? $_REQUEST['this_arrears'] : '';
		$prev_arrears =  (!empty($_REQUEST['prev_arrears'])) ? $_REQUEST['prev_arrears'] : '';
		$total_arrears = (!empty($_REQUEST['total_arrears'])) ? $_REQUEST['total_arrears'] : '';

		try{
			$send_data = array(
				'building_seq'=> $lm_building_seq,
				'room_num'=>$lm_room_num,
				'lm_year' => $lm_year,
				'lm_month' => $lm_month,
				'total_claim_fee' => $total_claim_fee,
				'total_income' => $total_income,
				'this_arrears' => $this_arrears,
				'prev_arrears' => $prev_arrears,
				'total_arrears' => $total_arrears
			);
			$this->ArrearsModel->modArrearsInfo($send_data);
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
