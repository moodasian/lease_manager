<?php


class Building extends CI_Controller
{
	/*
	 * 건물관리 Controller (건물 정보 등록, 수정, 삭제)
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BuildingModel', 'UserJoinModel'));
	}

	public function index(){
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		$this->load->view('/building/index');
	}

	public function getBuildingList(){
		$this->load->library('someclass');
		session_start();


		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];



		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$owner_name = (!empty($_REQUEST['building_owner_name'])) ? $_REQUEST['building_owner_name'] : '';

		$buildingInfo = $this->BuildingModel->getBuildingInfo($userId, $building_name, $owner_name);

		foreach($buildingInfo as $k => $v){
			$buildingInfo[$k]['regdate'] = date("Y-m-d", strtotime($buildingInfo[$k]['regdate']));
			$buildingInfo[$k]['building_owner_phone'] = $this->someclass->AES_Decode($buildingInfo[$k]['building_owner_phone']);
			$buildingInfo[$k]['user_name'] = $this->someclass->AES_Decode($buildingInfo[$k]['user_name']);
			$buildingInfo[$k]['user_phone'] = $this->someclass->AES_Decode($buildingInfo[$k]['user_phone']);
		}

		$return_arr = json_encode($buildingInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getBuildingInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';

		$buildingInfo = $this->BuildingModel->getBuildingInfoIndi($userId, $building_seq);

		$buildingInfo[0]['building_owner_phone'] = $this->someclass->AES_Decode($buildingInfo[0]['building_owner_phone']);

		$return_arr = json_encode($buildingInfo[0]);

		echo $return_arr;
	}

	public function registBuildingInfo(){
		$this->load->library('someclass');
		session_start();

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '무명빌딩';
		$building_owner_name = (!empty($_REQUEST['building_owner_name'])) ? $_REQUEST['building_owner_name'] : '배영일';
		$building_owner_phone = (!empty($_REQUEST['building_owner_phone'])) ? $_REQUEST['building_owner_phone'] : '배영일';
		$building_address = (!empty($_REQUEST['building_address'])) ? $_REQUEST['building_address'] : '경기도 광명시 목감로 96';
		$building_address_detail = (!empty($_REQUEST['building_address_detail'])) ? $_REQUEST['building_address_detail'] : '';
		$building_room_cnt = (!empty($_REQUEST['building_room_cnt'])) ? $_REQUEST['building_room_cnt'] : '0';
		$building_zipcode = (!empty($_REQUEST['building_zipcode'])) ? $_REQUEST['building_zipcode'] : '';
		$building_owner_phone = $this->someclass->AES_Encode($building_owner_phone);
		$building_bill_type = (!empty($_REQUEST['building_bill_type'])) ? $_REQUEST['building_bill_type'] : 'A';

		$checkBuildingExist = $this->BuildingModel->checkTwoBuilding($user_seq);

		if(!empty($checkBuildingExist[0])){
			$return_arr = array(
				'result' => true,
				'msg' => '이미 등록한 건물 정보가 존재합니다.'
			);
		}else{
			//귀속년도
			$claim_year = date("Y", time());
			$claim_month = date("m", time());

			//청구년도
			$bill_ym = date("Y-m", strtotime($claim_year."-".$claim_month." +1 month"));
			$bill_str = explode("-", $bill_ym);
			$bill_year = $bill_str[0];
			$bill_month = $bill_str[1];

			//귀속년 이전달
			$prev_ym = date("Y-m", strtotime($claim_year." -1 month"));
			$prev_str = explode("-", $prev_ym);
			$prev_year = $prev_str[0];
			$prev_month = $prev_str[1];

			//사용시작일
			$use_start_date = date("Y-m-d H:i:s", time());
			$use_end_date = date("Y-m-t H:i:s", time());

			$prev_start_date = date("Y-m-01 00:00:00", strtotime($use_start_date."-1 month"));
			$prev_end_date = date("Y-m-t 23:59:59", strtotime($use_end_date."-1 month"));


			$insert_arr = array(
				'lm_user_seq' => $user_seq,
				'building_name' => $building_name,
				'building_owner_name' => $building_owner_name,
				'building_owner_phone' => $building_owner_phone,
				'building_address' => $building_address,
				'building_address_detail' => $building_address_detail,
				'building_room_cnt' => $building_room_cnt,
				'building_zipcode' => $building_zipcode,
				'building_bill_type'=>$building_bill_type
			);


			try{

				//빌딩 정보 insert, building_seq 리턴
				$building_seq = $this->BuildingModel->insertBuildingInfo($insert_arr);


				//기본료 공백데이터 insert start
				//기본료 구분 (전기, 수도, 가스, 기타)
				$fee_gubun_arr = ['A', 'B', 'C', 'D'];

				for($i=0; $i<2; $i++){

					if($i==0){
						//최초 입력 이전달 insert
						$lm_charge_year = $prev_year;
						$lm_charge_month = $prev_month;
						$lm_bill_year = $claim_year;
						$lm_bill_month = $claim_month;
						$start_date = $prev_start_date;
						$end_date = $prev_end_date;
					}else{
						//최초 입력 이번달 insert
						$lm_charge_year = $claim_year;
						$lm_charge_month = $claim_month;
						$lm_bill_year = $bill_year;
						$lm_bill_month = $bill_month;
						$start_date = $use_start_date;
						$end_date = $use_end_date;
					}


					for($j=0; $j<count($fee_gubun_arr); $j++){
						//기본료 정보 빈 데이터 insert
						$basic_fee_arr = array(
							'lm_user_seq' => $user_seq,
							'lm_building_seq' => $building_seq,
							'fee_gubun' => $fee_gubun_arr[$j],
							'use_start_date' => $start_date,
							'use_end_date' => $end_date,
							'claim_year' => $lm_charge_year,
							'claim_month' => $lm_charge_month,
							'bill_year' => $lm_bill_year,
							'bill_month' => $lm_bill_month
						);

						$this->BuildingModel->insertBasicFeeInfo($basic_fee_arr);
						//기본료 공백데이터 insert 끝


						//지출 공백데이터 insert 시작
						$expense_arr = array(
							'lm_user_seq' => $user_seq,
							'lm_building_seq' => $building_seq,
							'lm_expense_year' => $lm_charge_year,
							'lm_expense_month' => $lm_charge_month
						);

						$this->BuildingModel->insertExpenseInfo($expense_arr);
						//지출 공백데이터 insert 끝

						//정산 공백데이터 insert 시작
						$calculation_arr = array(
							'lm_user_seq' => $user_seq,
							'lm_building_seq' => $building_seq,
							'lm_calculation_year' => $lm_charge_year,
							'lm_calculation_month' => $lm_charge_month
						);
						$this->BuildingModel->insertCalculationInfo($calculation_arr);
						//정산 공백데이터 insert 끝
					}
				}


				$return_arr = array(
					'result' => true,
					'msg' => '정상적으로 등록 되었습니다.'
				);
			}catch(Exception $e){
				$return_arr = array(
					'result' => false,
					'msg' => '등록에 실패하였습니다.'
				);
			}
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);
 		echo $return_arr;
	}

	public function modifyBuildingInfo(){
		$this->load->library('someclass');
		session_start();

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$gubun_flag = (!empty($_REQUEST['gubun_flag'])) ? $_REQUEST['gubun_flag'] : 'mod';

		if($gubun_flag == 'mod'){
			$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '무명빌딩';
			$building_owner_name = (!empty($_REQUEST['building_owner_name'])) ? $_REQUEST['building_owner_name'] : '배영일';
			$building_owner_phone = (!empty($_REQUEST['building_owner_phone'])) ? $_REQUEST['building_owner_phone'] : '배영일';
			$building_owner_phone = $this->someclass->AES_Encode($building_owner_phone);
			$building_address = (!empty($_REQUEST['building_address'])) ? $_REQUEST['building_address'] : '경기도 광명시 목감로 96';
			$building_address_detail = (!empty($_REQUEST['building_address_detail'])) ? $_REQUEST['building_address_detail'] : '';
			$building_room_cnt = (!empty($_REQUEST['building_room_cnt'])) ? $_REQUEST['building_room_cnt'] : '0';
			$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '0';
			$building_bill_type = (!empty($_REQUEST['building_bill_type'])) ? $_REQUEST['building_bill_type'] : 'A';


			$gubun_flag_var = "";
			if($gubun_flag == 'mod'){ $gubun_flag_var = "수정"; }else{ $gubun_flag_var = "삭제"; }

			$mod_array = array(
				'lm_user_seq' => $user_seq,
				'building_name' => $building_name,
				'building_owner_name' => $building_owner_name,
				'building_owner_phone' => $building_owner_phone,
				'building_address' => $building_address,
				'building_address_detail' => $building_address_detail,
				'building_room_cnt' => $building_room_cnt,
				'gubun_flag' => $gubun_flag,
				'building_seq' => $building_seq,
				'building_bill_type' => $building_bill_type
			);


			$res = $this->BuildingModel->modifyBuildingInfo($mod_array);


			if($res === true){
				$return_arr = array(
					'result' => true,
					'msg' => '정상적으로 수정 되었습니다.'
				);
			}else{
				$return_arr = array(
					'result' => false,
					'msg' => '수정에 실패하였습니다.'
				);
			}
		}else{
			$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '0';


			$res = $this->BuildingModel->deleteBuildingInfo($building_seq);

			if($res === true){
				$return_arr = array(
					'result' => true,
					'msg' => '정상적으로 삭제되었습니다.'
				);
			}else{
				$return_arr = array(
					'result' => false,
					'msg' => '삭제에 실패하였습니다.'
				);
			}
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}
}
