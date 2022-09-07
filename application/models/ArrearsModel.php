<?php


class ArrearsModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getArrearsList($data){

		$whereTxt = "WHERE 1=1";
		$whereTxtscnd = " WHERE 1=1 ";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
			$whereTxtscnd .= " AND lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['arrears_year'])){
			$whereTxt .= " AND A.lm_charge_year = '{$data['arrears_year']}' ";
		}
		if(!empty($data['arrears_month'])){
			$whereTxt .= " AND A.lm_charge_month = '{$data['arrears_month']}' ";
		}
		if(!empty($data['room_num'])){
			$whereTxt .= " AND A.lm_room_num = '{$data['room_num']}' ";
		}
		if(!empty($data['arrears_prev_year'])){
			$whereTxtscnd .= " AND arrears_year <= '{$data['arrears_prev_year']}' ";
		}

		if(!empty($data['arrears_prev_month'])){
			$whereTxtscnd .= " AND arrears_month <= '{$data['arrears_prev_month']}' ";
		}

		$query = "
		SELECT
			Y.lm_user_seq, 
			Y.building_name,
			Y.lm_building_seq,
			Y.lm_room_num, 
			IFNULL(FORMAT((lm_usage_cost + room_deposit + room_monthly_fee + room_maintenance_charge), 0), 0) AS total_claim_fee, 
			ifnull(FORMAT(total_income, 0), 0) AS total_income, 
			ifnull(FORMAT(arrears_cost_this, 0), 0) AS this_arrears, 
			ifnull(FORMAT(YG.arrears_cost, 0), 0) AS prev_arrears, 
			IFNULL(FORMAT((arrears_cost_this + YG.arrears_cost), 0), 0) AS total_arrears,
			lm_year,
			lm_month
		FROM (
			SELECT 
				building_name,
				lm_user_seq, 
				lm_building_seq,
				lm_room_num,
				MAX(seq) AS seq,
				SUM(lm_usage_meter) lm_usage_meter,
				SUM(lm_usage_amount) lm_usage_amount,
				SUM(lm_usage_cost) lm_usage_cost,
				SUM(room_deposit) room_deposit,
				SUM(room_monthly_fee) room_monthly_fee,
				SUM(room_maintenance_charge) room_maintenance_charge,
				SUM(room_etc_fee) room_etc_fee,
				MAX(lm_charge_year) lm_charge_year,
				MAX(lm_charge_month) lm_charge_month,
				MAX(first_income) AS first_income,
				MAX(second_income) AS second_income,
				MAX(third_income) AS third_income,
				(MAX(first_income) + MAX(second_income) + MAX(third_income)) AS total_income,
				MAX(arrears_cost) AS arrears_cost_this,
				MAX(lm_charge_year) AS lm_year,
				MAX(lm_charge_month) AS lm_month
			FROM (
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
					IFNULL(D.lm_rental_fee, IFNULL(C.room_monthly_fee, 0)) AS room_monthly_fee,
					IFNULL(D.lm_maintenanace_fee, IFNULL(C.room_maintenance_charge, 0)) AS room_maintenance_charge,
					IFNULL(D.lm_etc_fee, IFNULL(C.room_etc_fee, 0)) AS room_etc_fee,
					A.lm_charge_year,
					A.lm_charge_month,
					F.first_income,
					F.second_income,
					F.third_income,
					F.total_income,
					G.arrears_cost
				FROM lm_usagemanage A 
				LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
				LEFT JOIN lm_room_contract_info C ON A.lm_building_seq = C.lm_building_seq AND A.lm_room_num = C.lm_room_num
				LEFT OUTER JOIN lm_bill_info AS D ON A.lm_building_seq = D.lm_building_seq AND A.lm_room_num = D.lm_room_num AND A.lm_charge_year = D.lm_claim_year AND A.lm_charge_month = D.lm_claim_month
				LEFT OUTER JOIN lm_credit_info AS F ON A.lm_building_seq = F.lm_building_seq AND A.lm_room_num = F.lm_room_num AND A.lm_charge_year = F.income_year AND A.lm_charge_month = F.income_month
				LEFT OUTER JOIN lm_arrears_info AS G ON A.lm_building_seq = G.lm_building_seq AND A.lm_room_num = G.lm_room_num AND A.lm_charge_year = G.arrears_year AND A.lm_charge_month = G.arrears_month
				{$whereTxt}
				ORDER BY lm_charge_year DESC, lm_charge_month DESC, lm_usage_gubun ASC, lm_room_num ASC  
			) X
			GROUP BY building_name, lm_user_seq, lm_building_seq, lm_room_num
		) Y
		LEFT OUTER JOIN (
			SELECT 
			lm_building_seq,
			lm_room_num,
			SUM(arrears_cost) AS arrears_cost
			FROM lm_arrears_info
			{$whereTxtscnd}
			GROUP BY lm_building_seq, lm_room_num
		) YG ON Y.lm_building_seq = YG.lm_building_seq AND Y.lm_room_num = YG.lm_room_num 

		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modArrearsInfo($data){
		$query = "
			UPDATE lm_arrears_info SET 
			arrears_cost = '{$data['this_arrears']}'
			WHERE lm_building_seq='{$data['building_seq']}' and lm_room_num = '{$data['room_num']}' and arrears_year = '{$data['lm_year']}' and arrears_month = '{$data['lm_month']}'
		";
		$res = $this->lmsql->query($query);
		return $res;
	}
}
