<?php


class CalculationModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getCalculationList($data){
		$whereTxt = "WHERE 1=1";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['lm_calculation_year'])){
			$whereTxt .= " AND lm_calculation_year = '{$data['lm_calculation_year']}' ";
		}
		if(!empty($data['lm_calculation_month'])){
			$whereTxt .= " AND lm_calculation_month = '{$data['lm_calculation_month']}' ";
		}

		$query = "
		SELECT 
		A.seq,
		A.lm_user_seq,
		A.lm_building_seq,
		A.lm_calculation_year,
		A.lm_calculation_month,
		FORMAT(lm_total_expense, 0) AS lm_total_expense,
		FORMAT(lm_total_income, 0) AS lm_total_income,
		B.building_name
		FROM lm_calculation_info A
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		{$whereTxt}
		ORDER BY lm_calculation_year DESC, lm_calculation_month DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getCalculationIndi($data){
		$query = "
		SELECT 
		A.seq,
		A.lm_building_seq,
		A.lm_calculation_year as lm_year,
		A.lm_calculation_month as lm_month,
		FORMAT(lm_total_rental_fee, 0) AS lm_total_rental_fee,
		FORMAT(lm_total_management_fee, 0) AS lm_total_management_fee,
		FORMAT(lm_totaL_etc_fee, 0) AS lm_totaL_etc_fee,
		FORMAT(lm_total_expense, 0) AS lm_total_expense,
		FORMAT(lm_total_electro_income, 0) AS lm_total_electro_income,
		FORMAT(lm_total_water_income, 0) AS lm_total_water_income,
		FORMAT(lm_total_gas_income, 0) AS lm_total_gas_income,
		FORMAT(lm_total_etc_income, 0) AS lm_total_etc_income,
		FORMAT(lm_total_income, 0) AS lm_total_income,
		first_yn,
		B.building_name
		FROM lm_calculation_info A
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
		WHERE A.lm_building_seq = '{$data['building_seq']}' AND lm_calculation_year = '{$data['lm_year']}' AND lm_calculation_month = '{$data['lm_month']}'
		ORDER BY lm_calculation_year DESC, lm_calculation_month DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getCalculationIndifrom($data){
		$query = "
		SELECT 
			lm_building_seq,
			'{$data['lm_year']}' AS lm_year,
			'{$data['lm_month']}' AS lm_month,
			SUM(room_monthly_fee) AS lm_total_rental_fee,
			SUM(room_maintenance_charge) AS lm_total_management_fee,
			SUM(room_etc_fee) AS lm_totaL_etc_fee,
			MAX(lm_total_expense) lm_total_expense,
			SUM(lm_electro_fee) AS lm_total_electro_income,
			SUM(lm_water_fee) AS lm_total_water_income,
			SUM(lm_gas_fee) AS lm_total_gas_income,
			SUM(lm_etc_fee) AS lm_total_etc_income, 
			SUM(lm_total_fee) AS lm_total_income
		FROM (
			SELECT 
				A.lm_building_seq,
				A.room_num,
				ifnull(B.room_monthly_fee, 0) AS room_monthly_fee,
				ifnull(B.room_maintenance_charge, 0) AS room_maintenance_charge,
				IFNULL(B.room_etc_fee, 0) AS room_etc_fee,
				ifnull(C.lm_electro_fee, 0) AS lm_electro_fee,
				ifnull(C.lm_water_fee, 0) AS lm_water_fee,
				ifnull(C.lm_gas_fee, 0) AS lm_gas_fee,
				ifnull(C.lm_etc_fee, 0) AS lm_etc_fee,
				ifnull(C.lm_total_fee, 0) AS lm_total_fee,
				C.lm_bill_year,
				C.lm_bill_month,
				D.lm_total_fee AS lm_total_expense
			FROM lm_room_info AS A
			LEFT OUTER JOIN lm_room_contract_info AS B ON A.lm_building_seq = B.lm_building_seq AND A.room_num = B.lm_room_num
			LEFT OUTER JOIN lm_bill_info AS C ON A.lm_building_seq = C.lm_building_seq AND A.room_num = C.lm_room_num
			LEFT OUTER JOIN lm_expense_info AS D ON A.lm_building_seq = D.lm_building_seq
			WHERE 
			A.lm_building_seq = '{$data['building_seq']}'
			AND C.lm_claim_year = '{$data['lm_year']}'
			AND C.lm_claim_month = '{$data['lm_month']}'
			AND D.lm_expense_year = '{$data['lm_year']}'
			AND D.lm_expense_month = '{$data['lm_month']}'
		) X
		GROUP BY lm_building_seq
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modCalculationInfo($data){
		$query = "
		UPDATE lm_calculation_info SET 
			lm_total_rental_fee = '{$data['lm_total_rental_fee']}'
			,lm_total_management_fee = '{$data['lm_total_management_fee']}'
			,lm_total_etc_fee = '{$data['lm_total_etc_fee']}'
			,lm_total_expense = '{$data['lm_total_expense']}'
			,lm_total_electro_income = '{$data['lm_total_electro_income']}'
			,lm_total_water_income = '{$data['lm_total_water_income']}'
			,lm_total_gas_income = '{$data['lm_total_gas_income']}'
			,lm_total_etc_income = '{$data['lm_total_etc_income']}'
			,lm_total_income = '{$data['lm_total_income']}'
		, first_yn = 'Y'
		WHERE 
			lm_building_seq='{$data['building_seq']}'
			AND lm_calculation_year = '{$data['lm_calculation_year']}'
			AND lm_calculation_month = '{$data['lm_calculation_month']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function delCalculationInfo($data){
		$query = "
		UPDATE lm_calculation_info SET 
			lm_total_rental_fee = '0'
			,lm_total_management_fee = '0'
			,lm_total_etc_fee = '0'
			,lm_total_expense = '0'
			,lm_total_electro_income = '0'
			,lm_total_water_income = '0'
			,lm_total_gas_income = '0'
			,lm_total_etc_income = '0'
			,lm_total_income = '0'
			, first_yn = 'N'
		WHERE 
			lm_building_seq='{$data['building_seq']}'
			AND lm_calculation_year = '{$data['lm_calculation_year']}'
			AND lm_calculation_month = '{$data['lm_calculation_month']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}
}
