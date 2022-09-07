<?php


class LeaseModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getLeaseListInfo($userSeq, $building_name = null, $room_num = null){
		$whereTxt = "";

		$whereTxt .= "WHERE A.lm_user_seq = '{$userSeq}' ";

		if(!empty($building_name)){
			$whereTxt .= " AND A.lm_building_seq = '{$building_name}' ";
		}

		if(!empty($room_num)){
			$whereTxt .= " AND room_num = '{$room_num}' ";
		}

		$query = "
		SELECT 
			C.building_name,
			A.seq AS room_seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.room_num,
			ifnull(B.contract_YN, 'N') AS contact_YN,
			A.room_supply_area,
			A.room_exclusive_area,
			B.seq AS lrci_seq,
			B.room_pay_cate,
			B.room_deposit,
			B.room_monthly_fee,
			B.room_maintenance_charge,
			B.room_movein_date,
			B.room_expire_date,
			B.room_mod_contract_date,
			B.room_mod_expire_date,
			B.regdate,
			B.moddate,
		    ifnull(D.lm_tenant_name, '공실') as lm_tenant_name
		FROM lm_room_info AS A
		LEFT outer JOIN lm_room_contract_info AS B ON 
		A.lm_user_seq = B.lm_user_seq 
		AND A.lm_building_seq = B.lm_building_seq 
		AND A.room_num = B.lm_room_num
		LEFT JOIN lm_building_info AS C ON A.lm_building_seq = C.seq
		LEFT OUTER JOIN lm_tenant_info AS D ON A.lm_building_seq = D.lm_building_seq AND A.room_num = D.lm_room_num
		{$whereTxt}
		ORDER BY room_num ASC
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;

	}

	public function modLeasePerson(){

		$query = "";
		$res = $this->lmsql->query($query);
		return $res;

	}

	public function delLeaseRoomContractInfo($building_seq, $room_num){
		$query = "
			UPDATE lm_room_contract_info SET 
			contract_YN = 'N',
			room_pay_cate = null
			room_deposit = null, 
			room_monthly_fee = null,
			room_maintenance_charge = null,
			room_etc_fee = null,
			room_movein_date = null,
			room_expire_date = null,
			room_mod_contract_date = null,
			room_mod_expire_date = null
			WHERE lm_building_seq='{$building_seq}' and lm_room_num = '{$room_num}'
		";
		$res = $this->lmsql->query($query);
		return $res;

	}

	public function delLeaseTenantInfo($building_seq, $room_num){
		$query = "
			UPDATE lm_tenant_info SET 
				lm_tenant_status = '0' 
			WHERE lm_building_seq='{$building_seq}' and lm_room_num = '{$room_num}'
		";

		$res = $this->lmsql->query($query);
		return $res;
	}

	public function getLeaseInfoIndi($building_seq, $room_num){
		$query = "
		SELECT 
			C.building_name,
			A.seq AS room_seq,
			A.lm_user_seq,
			A.lm_building_seq,
			A.room_num,
			ifnull(B.contract_YN, 'N') AS contact_YN,
			A.room_supply_area,
			A.room_exclusive_area,
			B.seq AS lrci_seq,
			B.room_pay_cate,
			B.room_deposit,
			B.room_monthly_fee,
			B.room_maintenance_charge,
		    B.room_etc_fee,
			DATE_FORMAT(B.room_movein_date, '%Y-%m-%d') AS room_movein_date,
			DATE_FORMAT(B.room_expire_date, '%Y-%m-%d') AS room_expire_date,
			DATE_FORMAT(B.room_mod_contract_date, '%Y-%m-%d') AS room_mod_contract_date,
			DATE_FORMAT(B.room_mod_expire_date, '%Y-%m-%d') AS room_mod_expire_date,
			B.regdate,
			B.moddate,
			D.lm_tenant_name,
			D.lm_tenant_phone,
			D.lm_tenant_phone2,
			D.lm_tenant_email,
			lm_tenant_status
		FROM lm_room_info AS A
		LEFT outer JOIN lm_room_contract_info AS B ON A.lm_user_seq = B.lm_user_seq AND A.lm_building_seq = B.lm_building_seq AND A.room_num = B.lm_room_num
		LEFT JOIN lm_building_info AS C ON A.lm_building_seq = C.seq
		LEFT OUTER JOIN lm_tenant_info AS D ON A.lm_user_seq = D.lm_user_seq AND A.lm_building_seq = D.lm_building_seq AND A.room_num = D.lm_room_num
		WHERE A.lm_building_seq = '{$building_seq}' AND A.room_num = '{$room_num}';
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function checkContractInfo($building_seq, $room_num){
		$query = "
		select * from lm_room_contract_info where lm_building_seq = '{$building_seq}' and lm_room_num = '{$room_num}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function insertRoomContractInfo($data){
		$query = "
			INSERT INTO lm_room_contract_info(
			lm_user_seq, 
			lm_building_seq, 
			lm_room_num, 
			contract_YN, 
			room_pay_cate, 
			room_deposit, 
			room_monthly_fee,
			room_maintenance_charge,
			room_etc_fee,
			room_movein_date,
			room_expire_date,
			room_mod_contract_date,
			room_mod_expire_date
			)
			values (
			'{$data['lm_user_seq']}', 
			'{$data['building_seq']}', 
			'{$data['room_num']}', 
			'Y',
			'm',
			'{$data['room_deposit']}', 
			'{$data['room_monthly_fee']}', 
			'{$data['room_maintenance_charge']}', 
			'{$data['room_etc_fee']}', 
			'{$data['room_movein_date']}', 
			'{$data['room_expire_date']}', 
			'{$data['room_mod_contract_date']}', 
			'{$data['room_mod_expire_date']}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}


	public function modRoomContractInfo($data){
		$query = "
			UPDATE lm_room_contract_info 
			SET 
			    contract_YN = 'Y',
			    room_deposit = '{$data['room_deposit']}',
			    room_monthly_fee = '{$data['room_monthly_fee']}',
			    room_maintenance_charge = '{$data['room_maintenance_charge']}',
			    room_etc_fee = '{$data['room_etc_fee']}',
			    room_movein_date = '{$data['room_movein_date']}',
			    room_expire_date = '{$data['room_expire_date']}', 
			    room_mod_contract_date = '{$data['room_mod_contract_date']}', 
			    room_mod_expire_date = '{$data['room_mod_expire_date']}'
			WHERE 
				lm_user_seq = '{$data['lm_user_seq']}'
				and lm_building_seq='{$data['building_seq']}'
				and lm_room_num = '{$data['room_num']}'
		";
		$res = $this->lmsql->query($query);

		return $res;
	}

	public function insertTenantInfo($data){
		$query = "
			INSERT INTO lm_tenant_info(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_name, 
				lm_tenant_phone, 
				lm_tenant_phone2, 
				lm_tenant_email,
				lm_tenant_status
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['building_seq']}', 
				'{$data['room_num']}', 
				'{$data['tenant_name']}', 
				'{$data['tenant_phone']}', 
				'{$data['tenant_phone2']}', 
				'', 
				'1'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}

	public function modTenantInfo($data){
		$query = "
			UPDATE lm_tenant_info 
			SET 
			    lm_tenant_name = '{$data['tenant_name']}',
			    lm_tenant_phone = '{$data['tenant_phone']}',
			    lm_tenant_phone2 = '{$data['tenant_phone2']}',
			    lm_tenant_email = '',
			    lm_tenant_status = '1'
			WHERE 
				lm_user_seq = '{$data['lm_user_seq']}'
				and lm_building_seq='{$data['building_seq']}'
				and lm_room_num = '{$data['room_num']}'
		";
		$res = $this->lmsql->query($query);

		return $res;
	}

	public function checkTenantInfo($building_seq, $room_num){
		$query = "
		select * from lm_tenant_info 
		where lm_building_seq = '{$building_seq}' and lm_room_num = '{$room_num}' and lm_tenant_status = '1'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function insertCreditInfo($data){
		$query = "
			INSERT INTO lm_credit_info(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_seq, 
				income_year, 
				income_month
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_room_num']}', 
				'{$data['lm_tenant_seq']}', 
				'{$data['income_year']}', 
				'{$data['income_month']}'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}


	public function insertArrearsInfo($data){
		$query = "
			INSERT INTO lm_arrears_info(
				lm_user_seq, 
				lm_building_seq, 
				lm_room_num, 
				lm_tenant_seq, 
				arrears_year, 
				arrears_month
			)
			values (
				'{$data['lm_user_seq']}', 
				'{$data['lm_building_seq']}', 
				'{$data['lm_room_num']}', 
				'{$data['lm_tenant_seq']}', 
				'{$data['arrears_year']}', 
				'{$data['arrears_month']}'
			)
		";

		$this->lmsql->query($query);
		$res = $this->lmsql->insert_id();

		return $res;
	}
}
