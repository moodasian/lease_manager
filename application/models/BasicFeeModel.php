<?php


class BasicFeeModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getBasicFeeList($userSeq, $building_name = null, $fee_gubun = null, $lm_year = null, $lm_month = null){

		$whereTxt = "WHERE 1=1";

		if(!empty($userSeq)){
			$whereTxt .= " AND A.lm_user_seq = '{$userSeq}' ";
		}

		if(!empty($building_name)){
			$whereTxt .= " AND B.building_name = '{$building_name}' ";
		}
		if(!empty($fee_gubun)){
			$whereTxt .= " AND A.fee_gubun = '{$fee_gubun}' ";
		}
		if(!empty($lm_year)){
			$whereTxt .= " AND A.claim_year = '{$lm_year}' ";
		}
		if(!empty($lm_month)){
			$whereTxt .= "AND A.claim_month = '{$lm_month}' ";
		}

		$query = "
			SELECT
				A.lm_building_seq,
				A.fee_gubun,
				CASE WHEN A.fee_gubun = 'A' THEN '전기' WHEN A.fee_gubun = 'B' THEN '수도' WHEN A.fee_gubun = 'C' THEN '가스' WHEN A.fee_gubun = 'D' THEN '기타' END AS fee_gubun_var, 
				A.charge_fee_before,
				A.deduct_fee,
				A.deduct_reason,
				A.charge_fee_after,
				A.use_amount,
				A.use_basicfee_per,
				DATE_FORMAT(A.use_start_date, '%m-%d') AS use_start_date,
				DATE_FORMAT(A.use_end_date, '%m-%d') AS use_end_date,
				B.building_name, 
				B.building_room_cnt,
				A.claim_year,
				A.claim_month,
				A.bill_year,
				A.bill_month,
			       A.seq as seq
			FROM lm_basic_fee AS A 
			LEFT JOIN lm_building_info AS B ON A.lm_user_seq = B.lm_user_seq AND A.lm_building_seq = B.seq
			{$whereTxt}
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBasicFeeInfoIndi($seq, $building_seq){
		$query = "
		SELECT A.*, B.building_name FROM lm_basic_fee AS A 
		LEFT JOIN lm_building_info AS B ON A.lm_building_seq = B.seq
		WHERE A.seq = '{$seq}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function modBasicFeeInfo($data){
		$query = "
		UPDATE lm_basic_fee
		SET 
		charge_fee_before = '{$data['charge_fee_before']}'
		,deduct_fee = '{$data['deduct_fee']}'
		,deduct_reason = '{$data['deduct_reason']}'
		,charge_fee_after = '{$data['charge_fee_after']}'
		,use_amount = '{$data['use_amount']}'
		,use_basicfee_per = '{$data['use_basicfee_per']}'
		WHERE 
		seq='{$data['basicfee_seq']}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}
}
