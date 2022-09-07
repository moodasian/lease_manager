<?php


class Lease extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('BuildingModel', 'UserJoinModel', 'LeaseModel'));
	}

	public function index(){
		session_start();
		if(empty($_SESSION['user_id'])){
			echo ("<script>location.href='/LogIn';</script>");
		}

		//userId, userSeq >> session처리 추가
		//$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		//$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		//$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		//$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		//if($room_num == '선택해주세요'){
		//	$room_num = '';
		//}

		//lm_room_info를 기준으로 lm_room_contract_info, lm_tenant_info가 비었을 경우 left join으로 빈 값 가져옴
		//$leaseListInfo = $this->LeaseModel->getLeaseListInfo($userSeq, $building_name, $room_num);
		//$data = array();
		//$data['list'] = $leaseListInfo[0];
		$this->load->view('/lease/index');
	}



	public function paging($table, $currentPage = null){
		$blockCount = 10;
		$blockPage = 10;

		$rowCount = $this->Common_model->get_table_query_cnt($table, "*");

		if ($currentPage == null)
			$currentPage = 1;

		$totalPage = floor(($rowCount - 1) / $blockPage) + 1;

		if ($totalPage < $currentPage)
			$currentPage = $totalPage;

		if ($currentPage < 1)
			$currentPage = 1;

		$data['startCount'] = ($currentPage - 1) * $blockCount;

		$data['startPage'] = floor(($currentPage - 1) / $blockPage) * $blockPage + 1;
		$data['endPage'] = $data['startPage'] + $blockCount - 1;

		if ($data['endPage'] > $totalPage)
			$data['endPage'] = $totalPage;

		$data['limitArray'] = Array($data['startCount'], $blockCount);

		$data['currentPage'] = $currentPage;
		$data['blockPage'] = $blockPage;
		$data['totalPage'] = $totalPage;

		return $data;
	}

	public function getLeaseList(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		if($room_num == '선택해주세요'){
			$room_num = '';
		}

		//lm_room_info를 기준으로 lm_room_contract_info, lm_tenant_info가 비었을 경우 left join으로 빈 값 가져옴
		$leaseListInfo = $this->LeaseModel->getLeaseListInfo($userSeq, $building_name, $room_num);


		$return_arr = json_encode($leaseListInfo, JSON_UNESCAPED_UNICODE);

		echo $return_arr;
	}

	public function insertListPerson(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$building_seq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		//room_contract_info status 변경




		//tenant_info status 변경

	}

	public function deleteLeaseInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';

		try{
			//room_contract_info status 변경
			$res = $this->LeaseModel->delLeaseRoomContractInfo($building_seq, $room_num);

			//tenant_info status 변경
			$res2 = $this->LeaseModel->delLeaseTenantInfo($building_seq, $room_num);

			if($res === true && $res2 === true){
				$return_arr = array(
					'result' => true,
					'msg' => '성공적으로 삭제하였습니다.'
				);
			}
		}catch (Exception $e){
			$return_arr = array(
				'result' => false,
				'msg' => '오류가 발생했습니다.'
			);
		}
		$return_arr = json_encode($return_arr, JSON_UNESCAPED_UNICODE);


		echo $return_arr;
	}

	public function getLeaseInfoIndi(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';


		$leaseInfo = $this->LeaseModel->getLeaseInfoIndi($building_seq, $room_num);

		$return_arr = json_encode($leaseInfo[0], JSON_UNESCAPED_UNICODE);


		echo $return_arr;


	}

	public function modLeaseInfo(){
		$this->load->library('someclass');
		session_start();

		//userId, userSeq >> session처리 추가
		$userId = (!empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $_SESSION['user_id'];
		$userSeq = (!empty($_REQUEST['user_seq'])) ? $_REQUEST['user_seq'] : $_SESSION['user_seq'];

		$building_name = (!empty($_REQUEST['building_name'])) ? $_REQUEST['building_name'] : '';
		$building_seq = (!empty($_REQUEST['building_seq'])) ? $_REQUEST['building_seq'] : '';
		$room_num = (!empty($_REQUEST['room_num'])) ? $_REQUEST['room_num'] : '';
		$tenant_name = (!empty($_REQUEST['tenant_name'])) ? $_REQUEST['tenant_name'] : '';
		$tenant_phone = (!empty($_REQUEST['tenant_phone'])) ? $_REQUEST['tenant_phone'] : '';
		$tenant_phone2 = (!empty($_REQUEST['tenant_phone2'])) ? $_REQUEST['tenant_phone2'] : '';
		$room_deposit = (!empty($_REQUEST['room_deposit'])) ? $_REQUEST['room_deposit'] : '0';
		$room_movein_date = (!empty($_REQUEST['room_movein_date'])) ? $_REQUEST['room_movein_date'] : null;
		$room_monthly_fee = (!empty($_REQUEST['room_monthly_fee'])) ? $_REQUEST['room_monthly_fee'] : '0';
		$room_expire_date = (!empty($_REQUEST['room_expire_date'])) ? $_REQUEST['room_expire_date'] : null;
		$room_maintenance_charge = (!empty($_REQUEST['room_maintenance_charge'])) ? $_REQUEST['room_maintenance_charge'] : '0';
		$room_mod_contract_date = (!empty($_REQUEST['room_mod_contract_date'])) ? $_REQUEST['room_mod_contract_date'] : null;
		$room_etc_fee = (!empty($_REQUEST['room_etc_fee'])) ? $_REQUEST['room_etc_fee'] : '0';
		$room_mod_expire_date = (!empty($_REQUEST['room_mod_expire_date'])) ? $_REQUEST['room_mod_expire_date'] : null;


		//귀속년도
		$claim_year = date("Y", time());
		$claim_month = date("m", time());

		//청구년도
		$bill_ym = date("Y-m", strtotime($claim_year." +1 month"));
		$bill_str = explode("-", $bill_ym);
		$bill_year = $bill_str[0];
		$bill_month = $bill_str[1];

		//사용시작일
		$use_start_date = date("Y-m-d H:i:s", time());
		$use_end_date = date("Y-m-t H:i:s", time());


		try{
			//contract info 변경
			$contract_info_check = $this->LeaseModel->checkContractInfo($building_seq, $room_num);

			$contract_info_arr = array(
				'lm_user_seq' => $userSeq,
				'building_name' => $building_name,
				'building_seq' => $building_seq,
				'room_num' => $room_num,
				'room_deposit' => $room_deposit,
				'room_movein_date' => $room_movein_date,
				'room_monthly_fee' => $room_monthly_fee,
				'room_expire_date' => $room_expire_date,
				'room_maintenance_charge' => $room_maintenance_charge,
				'room_mod_contract_date' => $room_mod_contract_date,
				'room_etc_fee' => $room_etc_fee,
				'room_mod_expire_date' => $room_mod_expire_date
			);

			if(!empty($contract_info_check[0])){
				$this->LeaseModel->modRoomContractInfo($contract_info_arr);
			}else{
				$this->LeaseModel->insertRoomContractInfo($contract_info_arr);
			}

			//teant info
			$tenant_check = $this->LeaseModel->checkTenantInfo($building_seq, $room_num);

			$tenant_info_arr = array(
				'lm_user_seq' => $userSeq,
				'building_name' => $building_name,
				'building_seq' => $building_seq,
				'room_num' => $room_num,
				'tenant_name' => $tenant_name,
				'tenant_phone' => $tenant_phone,
				'tenant_phone2' => $tenant_phone2
			);
			if(!empty($tenant_check[0])){
				//정보가 있으면 update
				$this->LeaseModel->modTenantInfo($tenant_info_arr);
			}else{
				//없으면 insert
				$tenant_seq = $this->LeaseModel->insertTenantInfo($tenant_info_arr);

				$credit_insert_arr = array(
					'lm_user_seq' => $userSeq,
					'lm_building_seq' => $building_seq,
					'lm_room_num' => $room_num,
					'lm_tenant_seq' => $tenant_seq,
					'income_year' => $claim_year,
					'income_month' => $claim_month
				);
				$this->LeaseModel->insertCreditInfo($credit_insert_arr);

				$arrears_insert_arr = array(
					'lm_user_seq' => $userSeq,
					'lm_building_seq' => $building_seq,
					'lm_room_num' => $room_num,
					'lm_tenant_seq' => $tenant_seq,
					'arrears_year' => $claim_year,
					'arrears_month' => $claim_month
				);

				$this->LeaseModel->insertArrearsInfo($arrears_insert_arr);


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
