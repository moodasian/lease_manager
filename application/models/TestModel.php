<?php

class TestModel extends CI_Model
{
	function __construct(){
		parent::__construct();
		// 위에서 설정한 /application/config/database.php 파일에서
		// $db['myDB'] 설정값을 불러오겠다는 뜻
		$this->lmsql = $this->load->database('lmsql', TRUE);
	}

	function myDBTest() {

		$this->lmsql->select('*');
		$this->lmsql->from('test');
		$query = $this->lmsql->get()->result_array();

		print_r($query);

	}

	function getlmRoomContract(){
		$query = "
			select * from lm_room_info
			where lm_user_seq = 1
			and lm_building_seq = 1 
			and room_num between '101' and '105'
		";

		$res = $this->lmsql->query($query);
		$res = $res->result_array();

		return $res;
	}
}
