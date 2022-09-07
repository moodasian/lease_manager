<?php


class BuildingModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getBuildingInfoAll(){
		$query = "
			select * from lm_building_info where building_status = '1'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBuildingInfo($userId, $building_name = null, $owner_name = null){

		$whereTxt = "
		WHERE lbi.building_status = '1' 
		";
		if($userId == 'test'){
			$whereTxt .= "WHERE lu.user_id = '{$userId}'";
		}

		if(!empty($building_name)){
			if(!empty($whereTxt)){
				$whereTxt .= "AND lu.user_id = '{$userId}' AND lbi.building_name LIKE '%{$building_name}%' ";
			}else{
				$whereTxt .= "WHERE lu.user_id = '{$userId}' AND lbi.building_name LIKE '%{$building_name}%' ";
			}
		}

		if(!empty($owner_name)){
			if(!empty($whereTxt)){
				$whereTxt .= "AND lu.user_id = '{$userId}' AND lbi.building_owner_name LIKE '%{$owner_name}%' ";
			}else{
				$whereTxt .= "WHERE lu.user_id = '{$userId}' AND lbi.building_owner_name LIKE '%{$owner_name}%' ";
			}
		}
		$this->load->library('someclass');
		$query = "
			SELECT lbi.*, lu.user_id, lu.user_name, lu.user_phone, lu.user_email, lu.user_address
			FROM lm_building_info AS lbi
			LEFT JOIN lm_user AS lu ON lbi.lm_user_seq = lu.seq
			{$whereTxt}
			ORDER BY lbi.seq DESC 
		";


		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function checkTwoBuilding($user_seq){
		$query = "
		select * from lm_building_info where lm_user_seq = '{$user_seq}' and building_status = '1'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBuildingInfoIndi($userId, $building_seq){
		$whereTxt = "
		WHERE lu.user_id = '{$userId}' AND lbi.seq = '{$building_seq}'
		";

		$query = "
			SELECT lbi.*, lu.user_id, lu.user_name, lu.user_phone, lu.user_email, lu.user_address
			FROM lm_building_info AS lbi
			LEFT JOIN lm_user AS lu ON lbi.lm_user_seq = lu.seq
			{$whereTxt}
			ORDER BY lbi.seq DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function insertBuildingInfo($insert_arr){
		$this->load->library('someclass');

		$query = "
			INSERT INTO lm_building_info(lm_user_seq, building_name, building_address, building_address_detail, building_owner_name, building_owner_phone, building_room_cnt, building_zipcode)
			values ('{$insert_arr['lm_user_seq']}', '{$insert_arr['building_name']}', '{$insert_arr['building_address']}', '{$insert_arr['building_address_detail']}', '{$insert_arr['building_owner_name']}', '{$insert_arr['building_owner_phone']}', '{$insert_arr['building_room_cnt']}', '{$insert_arr['building_zipcode']}')
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}

	public function modifyBuildingInfo($insert_arr){
		$this->load->library('someclass');
		$gubun_flag = $insert_arr['gubun_flag'];

		if($gubun_flag == 'mod'){
			$query = "
			UPDATE lm_building_info
			SET 
			building_name = '{$insert_arr['building_name']}'
			,building_address = '{$insert_arr['building_address']}'
			,building_address_detail = '{$insert_arr['building_address_detail']}'
			,building_owner_name = '{$insert_arr['building_owner_name']}'
			,building_owner_phone = '{$insert_arr['building_owner_phone']}'
			,building_room_cnt = '{$insert_arr['building_room_cnt']}'
			,building_bill_type = '{$insert_arr['building_bill_type']}'
			WHERE 
			seq='{$insert_arr['building_seq']}'
			";

		}
		$res = $this->lmsql->query($query);

		return $res;
	}

	public function deleteBuildingInfo($building_seq){
		$query = "
			update lm_building_info set building_status = '0' where seq='{$building_seq}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertBasicFeeInfo($data){
		$query = "
			INSERT INTO lm_basic_fee(
				lm_user_seq, 
				lm_building_seq, 
				fee_gubun, 
				use_start_date, 
				use_end_date, 
				claim_year, 
				claim_month, 
				bill_year,
				bill_month
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['fee_gubun']}', 
				'{$data['use_start_date']}', 
				'{$data['use_end_date']}', 
				'{$data['claim_year']}', 
				'{$data['claim_month']}', 
				'{$data['bill_year']}',
				'{$data['bill_month']}'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}

	public function insertExpenseInfo($data){
		$query = "
			INSERT INTO lm_expense_info(
				lm_user_seq, 
				lm_building_seq, 
				lm_expense_year, 
				lm_expense_month
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_expense_year']}', 
				'{$data['lm_expense_month']}'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}

	public function insertCalculationInfo($data){
		$query = "
			INSERT INTO lm_calculation_info(
				lm_user_seq, 
				lm_building_seq, 
				lm_calculation_year, 
				lm_calculation_month,
				first_yn
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_calculation_year']}', 
				'{$data['lm_calculation_month']}',
			    'N'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}
}
