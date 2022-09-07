<?php


class ExitFacilityModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getExitFacilityTenantInfo($building_seq, $room_num){
		/*
		$whereTxt = "";
		$whereTxt = "
		WHERE lu.user_id = '{$data['userId']}' AND lbi.seq = '{$data['userId']}'
		";
		 */

		$query = "
			SELECT 
			A.*,
			B.building_name
			FROM lm_tenant_info A
			LEFT JOIN lm_building_info B ON A.lm_building_seq = B.seq
			WHERE lm_building_seq = '{$building_seq}' AND lm_room_num = '{$room_num}' AND lm_tenant_status = '1'
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}


	public function insertExitFacilityDocs($data){
		$query = "
		INSERT INTO 
			lm_exit_adjustment_info
			(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_seq, 
				lm_adjustment_ym,
				lm_exit_ymd,
				lm_room_deposit,
				lm_monthly_fee,
				lm_management_fee,
				lm_etc_fee,
				lm_electro_cost,
				lm_water_cost,
				lm_gas_cost,
				lm_etc_cost,
				lm_brokerage_fee,
				lm_deposit_pm,
				lm_monthly_fee_unpaid,
				lm_management_fee_unpaid,
				lm_electro_cost_unpaid,
				lm_water_cost_unpaid,
				lm_gas_cost_unpaid,
				lm_etc_cost_unpaid,
				lm_exit_cleaning_cost,
				lm_total_cost
			)
			values 
			(
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_room_num']}', 
				'{$data['lm_tenant_seq']}', 
				'{$data['lm_adjustment_ym']}', 
				'{$data['lm_exit_ymd']}', 
				'{$data['lm_room_deposit']}', 
				'{$data['lm_monthly_fee']}', 
				'{$data['lm_management_fee']}', 
				'{$data['lm_etc_fee']}',
				'{$data['lm_electro_cost']}',
				'{$data['lm_water_cost']}',
				'{$data['lm_gas_cost']}',
				'{$data['lm_etc_cost']}',
				'{$data['lm_brokerage_fee']}',
				'{$data['lm_deposit_pm']}',
				'{$data['lm_monthly_fee_unpaid']}',
				'{$data['lm_management_fee_unpaid']}',
				'{$data['lm_electro_cost_unpaid']}',
				'{$data['lm_water_cost_unpaid']}',
				'{$data['lm_gas_cost_unpaid']}',
				'{$data['lm_etc_cost_unpaid']}',
				'{$data['lm_exit_cleaning_cost']}',
				'{$data['lm_total_cost']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

}
