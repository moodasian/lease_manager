<?php


class ExpenseModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getExpenseList($data){

		$whereTxt = "WHERE 1=1 ";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['lm_expense_year'])){
			$whereTxt .= " AND lm_expense_year = '{$data['lm_expense_year']}' ";
		}
		if(!empty($data['lm_expense_month'])){
			$whereTxt .= " AND lm_expense_month = '{$data['lm_expense_month']}' ";
		}

		$query = "
		SELECT 
		A.seq,
		A.lm_user_seq,
		A.lm_building_seq,
		A.lm_expense_year,
		A.lm_expense_month,
		FORMAT(lm_total_fee, 0) AS lm_total_fee,
		B.building_name
		FROM lm_expense_info A
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		{$whereTxt}
		ORDER BY lm_expense_year DESC, lm_expense_month DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getExpenseInfoIndi($building_seq, $lm_year, $lm_month){
		$query = "
		SELECT 
		A.seq,
		A.lm_user_seq,
		A.lm_building_seq,
		A.lm_expense_year,
		A.lm_expense_month,
		FORMAT(lm_manager_pay, 0) AS lm_manager_pay,
		FORMAT(lm_cleaner_pay, 0) AS lm_cleaner_pay,
		FORMAT(lm_etc_pay, 0) AS lm_etc_pay,
		FORMAT(lm_maintence_bill, 0) AS lm_maintence_bill,
		FORMAT(lm_maintence_card, 0) AS lm_maintence_card,
		FORMAT((SELECT charge_fee_after FROM lm_basic_fee WHERE lm_building_seq = '{$building_seq}' AND fee_gubun = 'A' AND claim_year = '{$lm_year}' AND claim_month = '{$lm_month}'), 0) AS lm_electro_fee,
		FORMAT((SELECT charge_fee_after FROM lm_basic_fee WHERE lm_building_seq = '{$building_seq}' AND fee_gubun = 'B' AND claim_year = '{$lm_year}' AND claim_month = '{$lm_month}'), 0) AS lm_water_fee,
		FORMAT((SELECT charge_fee_after FROM lm_basic_fee WHERE lm_building_seq = '{$building_seq}' AND fee_gubun = 'C' AND claim_year = '{$lm_year}' AND claim_month = '{$lm_month}'), 0) AS lm_gas_fee,
		FORMAT((SELECT charge_fee_after FROM lm_basic_fee WHERE lm_building_seq = '{$building_seq}' AND fee_gubun = 'D' AND claim_year = '{$lm_year}' AND claim_month = '{$lm_month}'), 0) AS lm_etc_fee,
		FORMAT(lm_fire_management_fee, 0) AS lm_fire_management_fee,
		FORMAT(lm_electro_manage_fee, 0) AS lm_electro_manage_fee,
		FORMAT(lm_ev_fee, 0) AS lm_ev_fee,
		FORMAT(lm_secure_fee, 0) AS lm_secure_fee,
		FORMAT(lm_park_fee, 0) AS lm_park_fee,
		FORMAT(lm_broadcast_fee, 0) AS lm_broadcast_fee,
		FORMAT(lm_etc_manage_fee, 0) AS lm_etc_manage_fee,
		FORMAT(lm_etc_manage_fee_sec, 0) AS lm_etc_manage_fee_sec,
		FORMAT(lm_total_fee, 0) AS lm_total_fee,
		B.building_name
		FROM lm_expense_info A
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		WHERE lm_building_seq = '{$building_seq}' AND lm_expense_year = '{$lm_year}' AND lm_expense_month = '{$lm_month}'
		ORDER BY lm_expense_year DESC, lm_expense_month DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modExpenseInfo($data){
		$query = "
		UPDATE lm_expense_info SET 
			lm_manager_pay = '{$data['lm_manager_pay']}'
			,lm_cleaner_pay = '{$data['lm_cleaner_pay']}'
			,lm_etc_pay = '{$data['lm_etc_pay']}'
			,lm_maintence_bill = '{$data['lm_maintence_bill']}'
			,lm_maintence_card = '{$data['lm_maintence_card']}'
			,lm_fire_management_fee = '{$data['lm_fire_management_fee']}'
			,lm_electro_manage_fee = '{$data['lm_electro_manage_fee']}'
			,lm_ev_fee = '{$data['lm_ev_fee']}'
			,lm_secure_fee = '{$data['lm_secure_fee']}'
			,lm_broadcast_fee = '{$data['lm_broadcast_fee']}'
			,lm_etc_manage_fee = '{$data['lm_etc_manage_fee']}'
			,lm_total_fee = '{$data['lm_total_fee']}'
		WHERE 
			lm_building_seq='{$data['building_seq']}'
			AND lm_expense_year = '{$data['lm_year']}'
			AND lm_expense_month = '{$data['lm_month']}'
		";
		$res = $this->lmsql->query($query);

		return $res;
	}
}
