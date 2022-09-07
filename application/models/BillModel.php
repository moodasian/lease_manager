<?php


class BillModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getBillList($data){

		$whereTxt = "WHERE 1=1";
		$whereTxt_sub = "WHERE 1=1";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
			$whereTxt_sub .= "AND lm_building_seq = '{$data['building_seq']}'";
		}
		if(!empty($data['claim_year'])){
			$whereTxt .= " AND lm_charge_year = '{$data['claim_year']}' ";
			$whereTxt_sub .= " AND arrears_year <= '{$data['claim_year']}'";
		}
		if(!empty($data['claim_month'])){
			$whereTxt .= " AND lm_charge_month = '{$data['claim_month']}' ";
			$whereTxt_sub .= " AND arrears_month < '{$data['claim_month']}'";
		}
		if(!empty($data['room_num'])){
			$whereTxt .= " AND A.lm_room_num = '{$data['room_num']}' ";
		}

		$query = "
		SELECT 
			X.building_name,
			X.lm_user_seq,
			X.lm_building_seq,
			X.lm_room_num,
			sum(X.lm_usage_cost) AS lm_usage_cost_total,
			MAX(room_deposit) AS room_deposit,
		    max(room_maintenance_charge) as room_maintenance_charge,
			MAX(room_monthly_fee) AS room_monthly_fee,
			MAX(room_etc_fee) AS room_etc_fee,
			MAX(lm_charge_year) AS lm_charge_year,
			MAX(lm_charge_month) AS lm_charge_month,
			MAX(IFNULL(Y.arrears_cost, 0)) AS arrears_cost,
			(sum(X.lm_usage_cost) + MAX(room_monthly_fee) + MAX(room_etc_fee) + max(room_maintenance_charge)) AS total_claim_fee
		FROM (
			SELECT 
				B.building_name, 
				A.seq, 
				A.lm_user_seq, 
				A.lm_building_seq, 
				A.lm_room_num, 
				A.lm_usage_gubun, 
				IFNULL(A.lm_usage_meter, 0) AS lm_usage_meter, 
				IFNULL(A.lm_usage_amount, 0) AS lm_usage_amount, 
				IFNULL(A.lm_usage_cost, 0) AS lm_usage_cost, 
				IFNULL(C.room_deposit, 0) AS room_deposit, 
				IFNULL(C.room_monthly_fee, 0) AS room_monthly_fee, 
				IFNULL(C.room_maintenance_charge, 0) AS room_maintenance_charge, 
				IFNULL(C.room_etc_fee, 0) AS room_etc_fee, 
				A.lm_charge_year, 
				A.lm_charge_month
			FROM lm_usagemanage A
			LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
			LEFT JOIN lm_room_contract_info C ON A.lm_building_seq = C.lm_building_seq AND A.lm_room_num = C.lm_room_num
			{$whereTxt}
			ORDER BY lm_charge_year DESC, lm_charge_month DESC, A.lm_usage_gubun ASC, A.lm_room_num ASC
		) X 
		LEFT JOIN (
			SELECT  
				lm_building_seq,
				lm_room_num,
				SUM(arrears_cost) arrears_cost
			FROM lm_arrears_info 
			{$whereTxt_sub}
			GROUP BY lm_building_seq, lm_room_num
		) Y ON X.lm_building_seq = Y.lm_building_seq AND X.lm_room_num = Y.lm_room_num
		GROUP BY building_name, lm_user_seq, lm_building_seq, lm_room_num
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBillInfoIndi($building_name, $building_seq, $room_num, $claim_year, $claim_month){
		/*
		$query = "
		SELECT 
			B.building_name ,
			A.seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.lm_room_num,
			A.lm_usage_gubun,
			IFNULL(A.lm_usage_meter, 0) AS lm_usage_meter,
			IFNULL(A.lm_usage_amount, 0) AS lm_usage_amount,
			IFNULL(A.lm_usage_cost, 0) AS lm_usage_cost, 
			IFNULL(C.room_deposit, 0) AS room_deposit,
			IFNULL(C.room_monthly_fee, 0) AS room_monthly_fee,
			IFNULL(C.room_maintenance_charge, 0) AS room_maintenance_charge,
			IFNULL(C.room_etc_fee, 0) AS room_etc_fee,
			A.lm_charge_year,
			A.lm_charge_month
		FROM lm_usagemanage A 
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		LEFT JOIN lm_room_contract_info C ON A.lm_building_seq = C.lm_building_seq AND A.lm_room_num = C.lm_room_num
		WHERE B.building_name = '{$building_name}' AND lm_charge_year = '{$claim_year}' AND lm_charge_month = '{$claim_month}' AND A.lm_room_num = '{$room_num}'
		ORDER BY lm_charge_year DESC, lm_charge_month DESC, A.lm_usage_gubun ASC, A.lm_room_num ASC  
		";
		*/

		$query = "
		SELECT 
			X.building_name,
			X.lm_user_seq,
			X.lm_building_seq,
			X.lm_room_num,
			X.lm_usage_gubun,
			sum(X.lm_usage_cost) AS lm_usage_cost,
			MAX(X.lm_usage_gubun) lm_usage_gubun, 
			MAX(room_deposit) AS room_deposit,
			MAX(room_monthly_fee) AS room_monthly_fee,
		    MAX(room_maintenance_charge) AS room_maintenance_charge,
			MAX(room_etc_fee) AS room_etc_fee,
			MAX(lm_charge_year) AS lm_charge_year,
			MAX(lm_charge_month) AS lm_charge_month,
			MAX(IFNULL(Y.arrears_cost, 0)) AS arrears_cost,
			(sum(X.lm_usage_cost) + MAX(room_monthly_fee) + MAX(room_etc_fee) + MAX(room_maintenance_charge)) AS total_cost
		FROM (
			SELECT 
				B.building_name, 
				A.seq, 
				A.lm_user_seq, 
				A.lm_building_seq, 
				A.lm_room_num, 
				A.lm_usage_gubun, 
				IFNULL(A.lm_usage_meter, 0) AS lm_usage_meter, 
				IFNULL(A.lm_usage_amount, 0) AS lm_usage_amount, 
				IFNULL(A.lm_usage_cost, 0) AS lm_usage_cost, 
				IFNULL(C.room_deposit, 0) AS room_deposit, 
				IFNULL(C.room_monthly_fee, 0) AS room_monthly_fee, 
				IFNULL(C.room_maintenance_charge, 0) AS room_maintenance_charge, 
				IFNULL(C.room_etc_fee, 0) AS room_etc_fee, 
				A.lm_charge_year, 
				A.lm_charge_month
			FROM lm_usagemanage A
			LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
			LEFT JOIN lm_room_contract_info C ON A.lm_building_seq = C.lm_building_seq AND A.lm_room_num = C.lm_room_num
			WHERE B.building_name = '{$building_name}' AND lm_charge_year = '{$claim_year}' AND lm_charge_month = '{$claim_month}' AND A.lm_room_num = '{$room_num}'
			ORDER BY lm_charge_year DESC, lm_charge_month DESC, A.lm_usage_gubun ASC, A.lm_room_num ASC
		) X 
		LEFT JOIN (
			SELECT  
				lm_building_seq,
				lm_room_num,
				SUM(arrears_cost) arrears_cost
			FROM lm_arrears_info 
			WHERE 
			lm_building_seq = '{$building_seq}'
			AND arrears_year <= '{$claim_year}' 
			AND arrears_month <= '{$claim_month}'
			GROUP BY lm_building_seq, lm_room_num
		) Y ON X.lm_building_seq = Y.lm_building_seq AND X.lm_room_num = Y.lm_room_num
		GROUP BY building_name, lm_user_seq, lm_building_seq, lm_room_num, X.lm_usage_gubun
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function checkBillExist($lm_building_seq, $lm_room_num, $lm_bill_year, $lm_bill_month){
		$query = "
		select * from lm_bill_info
		where
			lm_building_seq = '{$lm_building_seq}' 
			AND lm_room_num = '{$lm_room_num}'
			AND lm_bill_year = '{$lm_bill_year}'
		  AND lm_bill_month = '{$lm_bill_month}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getTenantInfo($lm_building_seq, $lm_room_num){
		$query = "
		SELECT * FROM lm_tenant_info
		WHERE lm_building_seq = '{$lm_building_seq}'
		AND lm_room_num = '{$lm_room_num}'
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modBillInfo($data){
		$query = "
		UPDATE lm_bill_info SET 
			lm_rental_fee = '{$data['lm_rental_fee']}'
			,lm_maintenanace_fee = '{$data['lm_maintenanace_fee']}'
			,lm_etc_fee = '{$data['lm_etc_fee']}'
			,lm_electro_fee = '{$data['lm_electro_fee']}'
			,lm_water_fee = '{$data['lm_water_fee']}'
			,lm_gas_fee = '{$data['lm_gas_fee']}'
			,lm_etc_cost = '{$data['lm_etc_cost']}'
			,lm_arrears_fee = '{$data['lm_arrears_fee']}'
			,lm_total_fee = '{$data['lm_total_fee']}'
		WHERE 
			lm_building_seq='{$data['lm_building_seq']}'
			AND lm_room_num = '{$data['lm_room_num']}'
			AND lm_bill_year = '{$data['lm_bill_year']}'
			AND lm_bill_month = '{$data['lm_bill_month']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertBillInfo($data){
		$query = "
			INSERT INTO 
			lm_bill_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_seq, 
				lm_bill_year,
				lm_bill_month,
				lm_rental_fee,
				lm_maintenanace_fee,
				lm_etc_fee,
				lm_electro_fee,
				lm_water_fee,
				lm_gas_fee,
				lm_etc_cost,
				lm_arrears_fee,
				lm_total_fee
			)
			values 
			(
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_room_num']}', 
				'{$data['lm_tenant_seq']}', 
				'{$data['lm_bill_year']}', 
				'{$data['lm_bill_month']}', 
				'{$data['lm_rental_fee']}', 
				'{$data['lm_maintenanace_fee']}', 
				'{$data['lm_etc_fee']}', 
				'{$data['lm_electro_fee']}', 
				'{$data['lm_water_fee']}', 
				'{$data['lm_gas_fee']}', 
				'{$data['lm_etc_cost']}', 
				'{$data['lm_arrears_fee']}', 
				'{$data['lm_total_fee']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function getBillInfoIndimod($building_seq, $room_num, $claim_year, $claim_month){

		$query = "
		SELECT 
		A.*, 
		B.lm_tenant_name, 
		ifnull(lm_tenant_phone, IFNULL(lm_tenant_phone2, 0)) AS lm_tenant_phone,
		C.building_name
		FROM lm_bill_info A
		LEFT JOIN lm_tenant_info B ON A.lm_tenant_seq = B.seq 
		LEFT JOIN lm_building_info C on A.lm_building_seq = C.seq
		WHERE A.lm_building_seq = '{$building_seq}' AND A.lm_room_num = '{$room_num}' AND A.lm_claim_year = '{$claim_year}' AND A.lm_claim_month = '{$claim_month}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBillInfoTotal($userSeq, $thisy, $thism){
		$query = "
		SELECT lm_building_seq, lm_room_num
		FROM lm_bill_info
		WHERE lm_user_seq = '{$userSeq}' AND lm_claim_year = '{$thisy}' AND lm_claim_month = '{$thism}'
		GROUP BY lm_building_seq, lm_room_num
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
