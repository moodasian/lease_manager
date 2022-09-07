<?php


class ClaimModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getClaimList($data){
		$whereTxt = "WHERE 1=1";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['lm_year'])){
			$whereTxt .= " AND A.lm_charge_year = '{$data['lm_year']}' ";
		}
		if(!empty($data['lm_month'])){
			$whereTxt .= " AND A.lm_charge_month = '{$data['lm_month']}' ";
		}

		$query = "
		SELECT 
			lm_building_seq,
			lm_room_num,
		    building_name,
			MAX(room_monthly_fee) room_monthly_fee,
			MAX(room_maintenance_charge) room_maintenance_charge,
			MAX(room_etc_fee) room_etc_fee,
			group_concat(CONCAT(lm_usage_gubun_var,'-',lm_usage_cost) ORDER BY lm_usage_gubun ASC ) AS usage_info,
			lm_charge_year, 
			lm_charge_month
		FROM (
			SELECT
				A.lm_building_seq,
				B.building_name,
				A.lm_room_num,
				ifnull(C.room_monthly_fee, 0) AS room_monthly_fee,
				IFNULL(C.room_maintenance_charge, 0) AS room_maintenance_charge,
				IFNULL(C.room_etc_fee, 0) AS room_etc_fee,
				A.lm_usage_gubun,
				CASE WHEN A.lm_usage_gubun = 'A' THEN '전기' WHEN A.lm_usage_gubun = 'B' THEN '수도' WHEN A.lm_usage_gubun = 'C' THEN '가스' WHEN A.lm_usage_gubun = 'D' THEN '기타' END AS lm_usage_gubun_var, 
				ifnull(A.lm_usage_cost, 0) AS lm_usage_cost,
				A.lm_charge_year,
				A.lm_charge_month
			FROM lm_usagemanage A
			LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
			LEFT JOIN lm_room_contract_info C ON A.lm_building_seq = C.lm_building_seq AND A.lm_room_num = C.lm_room_num
			{$whereTxt}
		) X	
		GROUP BY lm_building_seq, building_name, lm_room_num, lm_charge_year, lm_charge_month
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
