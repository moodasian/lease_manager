<?php


class Facility extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BuildingModel', 'UserJoinModel', 'FacilityModel'));
	}

	public function index(){
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}
		$this->load->view('/facility/index');
	}


	public function getBuildingList(){
		session_start();
		$this->load->library('someclass');

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_SESSION['user_id'] : 'test';
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_SESSION['user_seq'] : '1';

		$get_array = array(
			'user_id' => $userId,
			'user_seq' => $userSeq
		);

		$buildingList = $this->BuildingModel->getBuildingInfo($get_array);

		$return_array = array();

		foreach($buildingList as $k => $v){
			$insert_array = array(
				'building_seq' => $v['seq'],
				'building_name' => $v['building_name']
			);
			array_push($return_array, $insert_array);
		}

		$return_array = json_encode($return_array, JSON_UNESCAPED_UNICODE);

		echo $return_array;

	}

	public function getFacilityList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$buildingSeq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		$get_array = array(
			'user_id' => $userId,
			'user_seq' => $user_seq,
			'building_seq' => $buildingSeq,
			'room_num' => $room_num
		);

		$buildingInfo = $this->FacilityModel->getFacilityInfo($get_array);

		foreach($buildingInfo as $key => $val){
			if(empty($val['contract_YN'])){
				$buildingInfo[$key]['contract_YN'] = 'N';
			}
		}

		$return_arr = json_encode($buildingInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getFacilitySelect(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$buildingSeq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';

		$get_array = array(
			'user_id' => $userId,
			'user_seq' => $user_seq,
			'building_seq' => $buildingSeq
		);
		$buildingInfo = $this->FacilityModel->getFacilitySelect($get_array);

		$return_arr = json_encode($buildingInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function getFacilityInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$user_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		$get_array = array(
			'user_id' => $userId,
			'user_seq' => $user_seq,
			'building_seq' => $building_seq,
			'room_num' => $room_num
		);


		$facilityInfo = $this->FacilityModel->getFacilityInfoIndi($get_array);

		if(!empty($facilityInfo)){
			$return_arr = json_encode($facilityInfo[0], JSON_UNESCAPED_UNICODE);
		}else{
			$return_arr = "";
		}


		echo $return_arr;
	}

	public function registFacilityInfo(){
		session_start();
		$this->load->library('someclass');

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$buildingSeq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '1';

		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '1';
		$room_supply_area = (!empty($_REQUEST['room_supply_area'])) ? $_REQUEST['room_supply_area'] : '1';
		$room_exclusive_area = (!empty($_REQUEST['room_exclusive_area'])) ? $_REQUEST['room_exclusive_area'] : '1';
		$room_room_cnt = (!empty($_REQUEST['room_room_cnt'])) ? $_REQUEST['room_room_cnt'] : '1';
		$room_living_room_cnt = (!empty($_REQUEST['room_living_room_cnt'])) ? $_REQUEST['room_living_room_cnt'] : '1';
		$room_kitchen_cnt = (!empty($_REQUEST['room_kitchen_cnt'])) ? $_REQUEST['room_kitchen_cnt'] : '1';
		$room_veranda_cnt = (!empty($_REQUEST['room_veranda_cnt'])) ? $_REQUEST['room_veranda_cnt'] : 'N';
		$room_bathroom_cnt = (!empty($_REQUEST['room_bathroom_cnt'])) ? $_REQUEST['room_bathroom_cnt'] : '1';
		$room_storage_cnt = (!empty($_REQUEST['room_storage_cnt'])) ? $_REQUEST['room_storage_cnt'] : '1';
		$lm_room_option = (!empty($_REQUEST['lm_room_option'])) ? $_REQUEST['lm_room_option'] : '1';
		$room_etc = (!empty($_REQUEST['room_etc'])) ? $_REQUEST['room_etc'] : '1';

		//귀속년도
		$claim_year = date("Y", time());
		$claim_month = date("m", time());

		//청구년도
		$bill_ym = date("Y-m", strtotime($claim_year." +1 month"));
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

		//ajax 보낼때 buioding_Seq 추가
		$check_array = array(
			'lm_user_seq' => $userSeq,
			'lm_building_seq' => $buildingSeq,
			'room_num' => $room_num,
		);

		$checkRoom = $this->FacilityModel->checkFacilityInfo($check_array);

		if(empty($checkRoom) || $checkRoom == 0){

			try{
				$insert_array = array(
					'lm_user_seq' => $userSeq,
					'lm_building_seq' => $buildingSeq,
					'room_num' => $room_num,
					'room_supply_area' => $room_supply_area,
					'room_exclusive_area' => $room_exclusive_area,
					'room_room_cnt' => $room_room_cnt,
					'room_living_room_cnt' => $room_living_room_cnt,
					'room_kitchen_cnt' => $room_kitchen_cnt,
					'room_veranda_cnt' => $room_veranda_cnt,
					'room_bathroom_cnt' => $room_bathroom_cnt,
					'room_storage_cnt' => $room_storage_cnt,
					'room_etc' => $room_etc,
					'room_status' => '1'
				);

				$insert_seq = $this->FacilityModel->insertFacilityInfo($insert_array);


				$insert_option_array = array(
					'lm_user_seq' => $userSeq,
					'lm_building_seq' => $buildingSeq,
					'lm_room_num' => $room_num,
					'lm_room_seq' => $insert_seq,
					'lm_room_option'=> $lm_room_option,
					'option_status' => '1'
				);

				$res = $this->FacilityModel->insertFacilityOptionInfo($insert_option_array);


				$gubun_fee = array('A', 'B', 'C', 'D');
				for($i=0; $i<2; $i++){

					if($i==0){
						//최초 입력 이전달 insert
						$lm_charge_year = $prev_year;
						$lm_charge_month = $prev_month;
						$lm_bill_year = $claim_year;
						$lm_bill_month = $claim_month;
					}else{
						//최초 입력 이번달 insert
						$lm_charge_year = $claim_year;
						$lm_charge_month = $claim_month;
						$lm_bill_year = $bill_year;
						$lm_bill_month = $bill_month;
					}


					for($k=0; $k<count($gubun_fee); $k++){
						$insert_bill_array = array(
							'lm_user_seq' => $userSeq,
							'lm_building_seq' => $buildingSeq,
							'lm_room_num' => $room_num,
							'lm_bill_year' => $lm_bill_year,
							'lm_bill_month' => $lm_bill_month,
							'lm_claim_year' => $lm_charge_year,
							'lm_claim_month' => $lm_charge_month
						);

						$this->FacilityModel->insertBillInfo($insert_bill_array);


						$insert_usagemanage_array = array(
							'lm_user_seq' => $userSeq,
							'lm_usage_gubun' => $gubun_fee[$k],
							'lm_building_seq' => $buildingSeq,
							'lm_room_num' => $room_num,
							'lm_charge_year' => $lm_charge_year,
							'lm_charge_month' => $lm_charge_month,
							'lm_bill_year' => $lm_bill_year,
							'lm_bill_month' => $lm_bill_month
						);

						$this->FacilityModel->insertUsageManageInfo($insert_usagemanage_array);
					}
				}

				//계약정보 공백데이터 입력
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




		}else{
			$return_arr = array(
				'result' => true,
				'msg' => '이미 등록된 시설입니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function modifyFacilityInfo(){
		$this->load->library('someclass');

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : 'test';
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : '1';
		$buildingSeq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '1';

		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '1';
		$room_supply_area = (!empty($_REQUEST['room_supply_area'])) ? $_REQUEST['room_supply_area'] : '1';
		$room_exclusive_area = (!empty($_REQUEST['room_exclusive_area'])) ? $_REQUEST['room_exclusive_area'] : '1';
		$room_room_cnt = (!empty($_REQUEST['room_room_cnt'])) ? $_REQUEST['room_room_cnt'] : '1';
		$room_living_room_cnt = (!empty($_REQUEST['room_living_room_cnt'])) ? $_REQUEST['room_living_room_cnt'] : '1';
		$room_kitchen_cnt = (!empty($_REQUEST['room_kitchen_cnt'])) ? $_REQUEST['room_kitchen_cnt'] : '1';
		$room_veranda_cnt = (!empty($_REQUEST['room_veranda_cnt'])) ? $_REQUEST['room_veranda_cnt'] : 'N';
		$room_bathroom_cnt = (!empty($_REQUEST['room_bathroom_cnt'])) ? $_REQUEST['room_bathroom_cnt'] : '1';
		$room_storage_cnt = (!empty($_REQUEST['room_storage_cnt'])) ? $_REQUEST['room_storage_cnt'] : '1';
		$lm_room_option = (!empty($_REQUEST['lm_room_option'])) ? $_REQUEST['lm_room_option'] : '1';
		$room_etc = (!empty($_REQUEST['room_etc'])) ? $_REQUEST['room_etc'] : '1';
		$action_gubun = (!empty($_REQUEST['action_gubun'])) ? $_REQUEST['action_gubun'] : '1';

		$gubun_flag_var = "";
		if($action_gubun == 'mod'){ $gubun_flag_var = "수정"; }else{ $gubun_flag_var = "삭제"; }

		$mod_array = array(
			'lm_user_seq' => $userSeq,
			'lm_building_seq' => $buildingSeq,
			'room_num' => $room_num,
			'room_supply_area' => $room_supply_area,
			'room_exclusive_area' => $room_exclusive_area,
			'room_room_cnt' => $room_room_cnt,
			'room_living_room_cnt' => $room_living_room_cnt,
			'room_kitchen_cnt' => $room_kitchen_cnt,
			'room_veranda_cnt' => $room_veranda_cnt,
			'room_bathroom_cnt' => $room_bathroom_cnt,
			'room_storage_cnt' => $room_storage_cnt,
			'room_etc' => $room_etc,
			'flag' => $action_gubun,
			'lm_room_option' => $lm_room_option
		);
		$res = $this->FacilityModel->modifyFacilityInfo($mod_array);

		if($res === true){
			$return_arr = array(
				'result' => true,
				'msg' => '정상적으로 '.$gubun_flag_var.' 되었습니다.'
			);
		}else{
			$return_arr = array(
				'result' => false,
				'msg' => $gubun_flag_var.'에 실패하였습니다.'
			);
		}

		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);

		echo $return_arr;

	}

	public function getFacilitySelectList(){
		session_start();
		$this->load->library('someclass');

		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$buildingSeq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '1';

		$res = $this->FacilityModel->getFacilitySelectList($buildingSeq);

		$return_arr = json_encode($res, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}
}
