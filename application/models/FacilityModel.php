<?php


class FacilityModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getFacilityInfoAll($building_seq){
		$query = "
		select * from lm_room_info where lm_building_seq = '{$building_seq}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();
		return $res;
	}

	public function getFacilityInfo($get_array){

		$whereTxt = "WHERE 1=1";

		if(!empty($get_array['building_seq'])){
			$whereTxt .= " AND lri.lm_building_seq = '{$get_array['building_seq']}' ";
		}

		if(!empty($get_array['room_num'])){
			$whereTxt .= "AND lri.room_num = '{$get_array['room_num']}' ";
		}

		$query = "
		SELECT lri.*, lroi.lm_room_option , lrci.contract_YN, lbi.building_name, lbi.seq AS building_seq
		FROM lm_room_info AS lri
		LEFT JOIN lm_room_option_info AS lroi ON lri.lm_user_seq = lroi.lm_user_seq 
		AND lri.lm_building_seq = lroi.lm_building_seq 
		AND lri.room_num = lroi.lm_room_num
		LEFT JOIN lm_room_contract_info AS lrci ON lri.lm_user_seq = lrci.lm_user_seq AND lri.lm_building_seq = lrci.lm_building_seq AND lri.room_num = lrci.lm_room_num
		LEFT JOIN lm_building_info AS lbi ON lri.lm_user_seq = lbi.lm_user_seq AND lri.lm_building_seq = lbi.seq
		{$whereTxt}
		ORDER BY lri.room_num ASC
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();
		return $res;
	}

	public function getFacilityInfoIndi($get_array){

		$whereTxt = "WHERE lri.lm_user_seq = '{$get_array['user_seq']}' ";

		if(!empty($get_array['building_seq'])){
			$whereTxt .= " AND lri.lm_building_seq = '{$get_array['building_seq']}' ";
		}
		if(!empty($get_array['room_num'])){
			$whereTxt .= " AND lri.room_num = '{$get_array['room_num']}' ";
		}


		$query = "
		SELECT *
		FROM lm_room_info AS lri
		LEFT JOIN lm_room_option_info AS lroi ON lri.lm_building_seq = lroi.lm_building_seq AND lri.room_num = lroi.lm_room_num
		{$whereTxt}
		ORDER BY lri.regdate DESC
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getFacilitySelect($data){
		$query = "
		SELECT room_num FROM lm_room_info WHERE lm_building_seq = '{$data['building_seq']}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function checkFacilityInfo($check_arr){
		$query = "
		SELECT lri.*, lroi.lm_room_option FROM lm_room_info AS lri
		LEFT JOIN lm_room_option_info AS lroi ON lri.lm_user_seq = lroi.lm_user_seq AND lri.lm_building_seq = lroi.lm_building_seq AND lri.room_num = lroi.lm_room_num
		WHERE lri.lm_user_seq = '{$check_arr['lm_user_seq']}'
		AND lri.lm_building_seq = '{$check_arr['lm_building_seq']}'
		AND lri.room_num = '{$check_arr['room_num']}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->num_rows();

		return $res;
	}

	public function insertFacilityInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_room_info
			(
				lm_user_seq, 
				lm_building_seq, 
				room_num, 
				room_supply_area, 
				room_exclusive_area, 
				room_room_cnt, 
				room_living_room_cnt,
				room_kitchen_cnt,
				room_veranda_cnt,
				room_bathroom_cnt,
				room_storage_cnt,
				room_etc
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['room_num']}', 
				'{$insert_arr['room_supply_area']}', 
				'{$insert_arr['room_exclusive_area']}', 
				'{$insert_arr['room_room_cnt']}', 
				'{$insert_arr['room_living_room_cnt']}',
				'{$insert_arr['room_kitchen_cnt']}',
				'{$insert_arr['room_veranda_cnt']}',
				'{$insert_arr['room_bathroom_cnt']}',
				'{$insert_arr['room_storage_cnt']}',
				'{$insert_arr['room_etc']}'
			)
		";

		$res = $this->lmsql->query($query);
		if($res){
			$last_id = $this->lmsql->insert_id($query);
		}

		return $last_id;
	}

	public function insertFacilityOptionInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_room_option_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_seq, 
				lm_room_num, 
				lm_room_option
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['lm_room_seq']}', 
				'{$insert_arr['lm_room_num']}', 
				'{$insert_arr['lm_room_option']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function modifyFacilityInfo($insert_arr){
		$flag = $insert_arr['flag'];


	}

	public function insertBillInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_bill_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_bill_year,
			 	lm_bill_month,
			 	lm_claim_year,
			 	lm_claim_month
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['lm_room_num']}', 
				'{$insert_arr['lm_bill_year']}', 
				'{$insert_arr['lm_bill_month']}',
			 '{$insert_arr['lm_claim_year']}',
			 '{$insert_arr['lm_claim_month']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertCreditInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_credit_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				income_year,
			 	income_month
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['lm_room_num']}', 
				'{$insert_arr['income_year']}', 
				'{$insert_arr['income_month']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertArrearsInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_arrears_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				arrears_year,
			 	arrears_month
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['lm_room_num']}', 
				'{$insert_arr['arrears_year']}', 
				'{$insert_arr['arrears_month']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertUsageManageInfo($insert_arr){
		$query = "
			INSERT INTO 
			lm_usagemanage
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_charge_year,
				lm_charge_month,
				lm_bill_year,
				lm_bill_month,
				lm_usage_gubun
			)
			values 
			(
				'{$insert_arr['lm_user_seq']}', 
				'{$insert_arr['lm_building_seq']}', 
				'{$insert_arr['lm_room_num']}', 
				'{$insert_arr['lm_charge_year']}', 
				'{$insert_arr['lm_charge_month']}',
				'{$insert_arr['lm_bill_year']}',
			 	'{$insert_arr['lm_bill_month']}',
			 	'{$insert_arr['lm_usage_gubun']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function getFacilitySelectList($building_seq){
		$query = "
		select * from lm_room_info where lm_building_seq = '{$building_seq}' order by room_num ASC
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
