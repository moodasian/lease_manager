<?php


class CreditModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getCreditList($data){
		$whereTxt = "WHERE 1=1";

		if(!empty($data['building_seq'])){
			$whereTxt .= " AND A.lm_building_seq = '{$data['building_seq']}' ";
		}
		if(!empty($data['lm_charge_year'])){
			$whereTxt .= " AND lm_charge_year = '{$data['lm_charge_year']}' ";
		}
		if(!empty($data['lm_charge_month'])){
			$whereTxt .= " AND lm_charge_month = '{$data['lm_charge_month']}' ";
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
			FORMAT(first_income, 0) AS first_income,
			FORMAT(second_income, 0) AS second_income,
			FORMAT(third_income, 0) AS third_income,
			format((first_income + second_income + third_income), 0) AS total_income,
			income_year,
			income_month,
			B.building_name
			FROM lm_credit_info A
			LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq 
			{$whereTxt}
			order by A.lm_room_num ASC 
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getCreditListIndi($data){
		$query = "
		SELECT
			A.seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.lm_room_num,
			FORMAT(first_income, 0) AS first_income,
			FORMAT(second_income, 0) AS second_income,
			FORMAT(third_income, 0) AS third_income,
			format((first_income + second_income + third_income), 0) AS total_income,
			income_year,
			income_month,
			B.building_name
		FROM lm_credit_info A
		LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq 
		WHERE B.building_name = '{$data['building_name']}' AND lm_room_num = {$data['room_num']} AND income_year = '{$data['credit_year']}' AND income_month = '{$data['credit_month']}'
		order by A.seq desc 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function checkCreditExist($lm_building_seq, $lm_room_num, $credit_year, $credit_month){
		$query = "
		SELECT * FROM lm_credit_info
		WHERE lm_building_seq = '{$lm_building_seq}'
		AND lm_room_num = '{$lm_room_num}'
		AND income_year = '{$credit_year}'
		AND income_month = '{$credit_month}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}


	public function modCreditInfo($data){
		$query = "
		UPDATE lm_credit_info SET 
			first_income = '{$data['first_income']}'
			,second_income = '{$data['second_income']}'
			,third_income = '{$data['third_income']}'
			,total_income = '{$data['total_income']}'
		WHERE 
			lm_building_seq='{$data['lm_building_seq']}'
			AND lm_room_num = '{$data['lm_room_num']}'
			AND income_year = '{$data['income_year']}'
			AND income_month = '{$data['income_month']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertCreditInfo($data){
		$query = "
			INSERT INTO 
			lm_credit_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_seq, 
				income_year,
				income_month,
				first_income,
				second_income,
				third_income,
				total_income
			)
			values 
			(
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_room_num']}', 
				'{$data['lm_tenant_seq']}', 
				'{$data['income_year']}', 
				'{$data['income_month']}', 
				'{$data['first_income']}', 
				'{$data['second_income']}', 
				'{$data['third_income']}', 
				'{$data['total_income']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}
}
