<?php


class IndexModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	public function getNoticeList(){

		$query = "
		SELECT * FROM lm_notice WHERE notice_status = '1'
		ORDER BY notice_regdate ASC  
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getQnAList(){

		$query = "
		SELECT * FROM lm_qna WHERE qna_status = '1' and qna_mother_seq is null ORDER BY qna_regdate DESC 
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getAdminInfo($data){
		$query = "
		select * from lm_user where user_id = '{$data}' and `status` = '1'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getNoticeInfoIndi($seq){
		$query = "
		select * from lm_notice where seq = '{$seq}'
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function registNotice($title, $contents, $filename){
		$query = "
		INSERT INTO lm_notice(
				notice_title, 
				notice_content, 
				notice_author,
		        notice_file_root,
		        notice_file_name
			)
			values (
				'{$title}', 
				'{$contents}', 
				'admin',
			    '/uploads/',
			    '{$filename}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function getQnAInfoIndi($seq){
		$query = "
		SELECT * FROM lm_qna WHERE seq = '{$seq}' OR qna_mother_seq = '{$seq}'
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function registQnA($qna_title, $qna_contents, $upload_qna_filename){
		$query = "
		INSERT INTO lm_qna(
			qna_title, 
			qna_content,
			qna_file_root,
			qna_file_name
			)
			values (
				'{$qna_title}', 
				'{$qna_contents}',
			        '/uploads/',
			        '{$upload_qna_filename}'
			)
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function modQnA($qna_seq, $qna_title, $qna_contents, $qna_a_contents){
		$query = "
		UPDATE lm_qna
		SET 
		qna_title = '{$qna_title}'
		,qna_content = '{$qna_contents}'
		WHERE 
		seq='{$qna_seq}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function checkAnswer($qna_seq){
		$query = "
		select * from lm_qna where qna_mother_seq = '{$qna_seq}'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function insertA($qna_seq, $qna_a_contents){
		$query = "
		INSERT INTO lm_qna(
				qna_mother_seq, 
				qna_content
			)
			values (
				'{$qna_seq}', 
				'{$qna_a_contents}'
			)
		";


		$res = $this->lmsql->query($query);

		return $res;
	}

	public function updateA($qna_seq, $qna_a_contents){
		$query = "
		UPDATE lm_qna
		SET qna_content = '{$qna_a_contents}'
		WHERE 
		qna_mother_seq='{$qna_seq}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function updateStatus($qna_seq){
		$query = "
		UPDATE lm_qna
		SET qna_reply_status = '1'
		WHERE 
		seq='{$qna_seq}'
		";

		$res = $this->lmsql->query($query);

		return $res;
	}

	public function getBuildingInfo($userSeq){
		$query = "
		select * from lm_building_info where lm_user_seq = '{$userSeq}'
		";
		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBuildingSummaryRight($building_seq){
		$query = "
		SELECT 
			A.building_name, 
			A.seq AS building_seq, 
			FORMAT((SELECT SUM(lm_usage_cost) AS lm_usage_cost FROM lm_usagemanage WHERE lm_building_seq = {$building_seq}), 0) AS lm_usage_cost, 
			FORMAT((SELECT SUM(total_income) AS total_income FROM lm_credit_info WHERE lm_building_seq = {$building_seq}), 0) AS total_income, 
			FORMAT((SELECT SUM(arrears_cost) AS total_arrears FROM lm_arrears_info WHERE lm_building_seq = {$building_seq}), 0) AS total_arrears, 
			FORMAT((SELECT SUM(lm_total_fee) AS total_expense FROM lm_expense_info WHERE lm_building_seq = {$building_seq}), 0) AS total_expense 
		FROM lm_building_info AS A
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBuildingSummaryAccum($lm_year, $lm_month, $building_seq){
		$query = "
		SELECT 
			A.building_name,
			A.seq AS building_seq,
			FORMAT(IFNULL((SELECT SUM(lm_usage_cost) AS lm_usage_cost FROM lm_usagemanage WHERE lm_charge_year = '{$lm_year}' AND lm_charge_month = '{$lm_month}'), 0), 0) AS lm_usage_cost,
			FORMAT(IFNULL((SELECT SUM(total_income) AS total_income FROM lm_credit_info WHERE income_year = '{$lm_year}' AND income_month = '{$lm_month}'), 0), 0) AS total_income, 
			FORMAT(IFNULL((SELECT SUM(arrears_cost) AS total_arrears FROM lm_arrears_info WHERE arrears_year = '{$lm_year}' AND arrears_month = '{$lm_month}'), 0), 0) AS total_arrears, 
			FORMAT(IFNULL((SELECT SUM(lm_total_fee) AS total_expense FROM lm_expense_info WHERE lm_expense_year = '{$lm_year}' AND lm_expense_month = '{$lm_month}'), 0), 0) AS total_expense 
		FROM lm_building_info AS A
		WHERE seq = {$building_seq}
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}

	public function getBuildingSeq($userSeq){
		$query = "
		SELECT * FROM lm_building_info where lm_user_seq = {$userSeq}
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;

	}
}
