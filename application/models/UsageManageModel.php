<?php


class UsageManageModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getUsageManageList($data){
		$whereTxt = "WHERE 1=1";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['fee_gubun'])){
			$whereTxt .= " AND A.lm_usage_gubun = '{$data['fee_gubun']}' ";
		}
		if(!empty($data['claim_year'])){
			$whereTxt .= " AND lm_charge_year = '{$data['claim_year']}' ";
		}
		if(!empty($data['claim_month'])){
			$whereTxt .= " AND lm_charge_month = '{$data['claim_month']}' ";
		}
		if(!empty($data['room_num'])){
			$whereTxt .= " AND A.lm_room_num = '{$data['room_num']}' ";
		}

		$query = "
		SELECT 
			A.seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.lm_room_num,
			A.lm_usage_gubun,
			IFNULL(A.lm_usage_meter, 0) AS lm_usage_meter,
			IFNULL(A.lm_usage_amount, 0) AS lm_usage_amount,
			IFNULL(A.lm_usage_cost, 0) AS lm_usage_cost, 
			A.lm_charge_year,
			A.lm_charge_month,
			B.building_name 
		FROM lm_usagemanage A 
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		{$whereTxt}
		ORDER BY lm_charge_year DESC, lm_charge_month DESC, lm_room_num ASC , lm_usage_gubun ASC 
		";


		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getPrevClaimInfo($userSeq){
		$query = "                                  
		SELECT                                           
			lm_charge_year,                                  
			lm_charge_month                                  
		FROM lm_usagemanage                              
		GROUP BY lm_charge_year, lm_charge_month         
		ORDER BY lm_charge_year ASC, lm_charge_month ASC 
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getPrevClaimList($send_data){
		$query = "
		SELECT 
			seq,
			lm_user_seq,
			lm_building_seq,
			lm_room_num,
			lm_usage_gubun,
			IFNULL(lm_usage_meter, 0) lm_usage_meter,
			IFNULL(lm_usage_amount, 0) lm_usage_amount,
			IFNULL(lm_usage_cost, 0) lm_usage_cost,
			lm_charge_year,
			lm_charge_month,
			( SELECT use_amount FROM lm_basic_fee WHERE lm_building_seq = '{$send_data['building_seq']}' AND fee_gubun = '{$send_data['fee_gubun']}' AND claim_year = '{$send_data['claim_year']}' AND claim_month = '{$send_data['claim_month']}'  ) AS use_amount,
			( SELECT use_basicfee_per FROM lm_basic_fee  WHERE lm_building_seq = '{$send_data['building_seq']}' AND fee_gubun = '{$send_data['fee_gubun']}' AND claim_year = '{$send_data['claim_year']}' AND claim_month = '{$send_data['claim_month']}'  ) as basicfee
		FROM lm_usagemanage
		WHERE 
		lm_user_seq = '{$send_data['user_seq']}' 
		AND lm_building_seq = '{$send_data['building_seq']}'
		AND lm_usage_gubun = '{$send_data['fee_gubun']}'
		AND lm_charge_year = '{$send_data['claim_year']}'
		AND lm_charge_month = '{$send_data['claim_month']}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modUsageManageInfo($data){
		$query = "
		UPDATE lm_usagemanage SET 
			lm_usage_gubun = '{$data['lm_usage_gubun']}'
			,lm_usage_meter = '{$data['lm_usage_meter']}'
			,lm_usage_amount = '{$data['lm_usage_amount']}'
			,lm_usage_cost = '{$data['lm_usage_cost']}'
			,lm_charge_year = '{$data['lm_charge_year']}'
			,lm_charge_month = '{$data['lm_charge_month']}'
		WHERE 
			lm_building_seq='{$data['lm_building_seq']}' 
			AND lm_room_num = '{$data['lm_room_num']}'
			AND lm_usage_gubun = '{$data['lm_usage_gubun']}'
			AND lm_charge_year = '{$data['lm_charge_year']}'
			AND lm_charge_month = '{$data['lm_charge_month']}'
		";


		$res = $this->lmsql->query($query);

		return $res;
	}

	public function modUsageManageInfo_prev($data){
		$query = "
		UPDATE lm_usagemanage SET 
			lm_usage_gubun = '{$data['lm_usage_gubun']}'
			,lm_usage_meter = '{$data['lm_usage_meter']}'
		WHERE 
			lm_building_seq='{$data['lm_building_seq']}' 
			AND lm_room_num = '{$data['lm_room_num']}'
			AND lm_usage_gubun = '{$data['lm_usage_gubun']}'
			AND lm_charge_year = '{$data['lm_charge_year']}'
			AND lm_charge_month = '{$data['lm_charge_month']}'
		";


		$res = $this->lmsql->query($query);

		return $res;
	}

	public function getUsageManageInfoIndi($seq){
		$query = "
		SELECT 
			A.seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.lm_room_num,
			A.lm_usage_gubun,
			IFNULL(A.lm_usage_meter, 0) AS lm_usage_meter,
			IFNULL(A.lm_usage_amount, 0) AS lm_usage_amount,
			IFNULL(A.lm_usage_cost, 0) AS lm_usage_cost, 
			A.lm_charge_year,
			A.lm_charge_month,
			B.building_name 
		FROM lm_usagemanage A 
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		WHERE A.seq = '{$seq}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modUsageManageInfoIndi($data){
		$query = "
		UPDATE lm_usagemanage SET 
			lm_usage_meter = '{$data['lm_usage_meter']}'
			,lm_usage_amount = '{$data['lm_usage_amount']}'
			,lm_usage_cost = '{$data['lm_usage_cost']}'
		WHERE 
			seq='{$data['seq']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}
	public function checkPrevPrev($building_seq, $room_num, $usage_gubun, $lm_year, $lm_month){
		$query = "
		

SELECT A.*, B.use_basicfee_per
FROM lm_usagemanage A
LEFT JOIN lm_basic_fee B ON A.lm_user_seq = B.lm_user_seq AND A.lm_building_seq = B.lm_building_seq AND A.lm_usage_gubun = B.fee_gubun AND A.lm_charge_year = B.claim_year AND A.lm_charge_month = B.claim_month
WHERE 
	A.lm_building_seq = '{$building_seq}' 
	AND A.lm_room_num = '{$room_num}' 
	AND A.lm_usage_gubun = '{$usage_gubun}' 
	AND A.lm_charge_year = '{$lm_year}' 
	AND A.lm_charge_month = '{$lm_month}'
		
		
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
